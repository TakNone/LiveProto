#!/usr/bin/env bash

LOG(){
	printf "\n=> %s\n" "$*";
}
ERR(){
	printf "\n[ERROR] %s\n" "$*" >&2;
}

# prefer sudo when not root #

SUDO=""
if [ "$(id -u)" -ne 0 ]; then
	if command -v sudo >/dev/null 2>&1; then SUDO="sudo"; else
		ERR "Not root and sudo not found , some install steps may fail"
	fi
fi

# detect package manager #

PKG=""
if command -v apt-get >/dev/null 2>&1; then PKG="apt"; fi
if command -v dnf >/dev/null 2>&1; then PKG="dnf"; fi
if command -v yum >/dev/null 2>&1 && [ -z "$PKG" ]; then PKG="yum"; fi
if command -v pacman >/dev/null 2>&1; then PKG="pacman"; fi
if command -v apk >/dev/null 2>&1; then PKG="apk"; fi
if command -v brew >/dev/null 2>&1; then PKG="brew"; fi

LOG "Package manager detected : ${PKG:-none}"

install_apt(){
	LOG "apt : updating and installing common packages ..."
	$SUDO apt-get update -y || true
	$SUDO apt-get upgrade -y || true
	$SUDO apt-get install -y software-properties-common ca-certificates curl wget git unzip || true
	# Add ondrej PPA if available for newer php #
	if ! grep -RHiE 'ppa:ondrej/php|launchpad\.net/ondrej/php|ondrej/php' /etc/apt/sources.list /etc/apt/sources.list.d 2>/dev/null; then
		LOG "Adding ondrej/php PPA (if supported) ..."
		$SUDO add-apt-repository -y ppa:ondrej/php >/dev/null 2>&1 || true
		$SUDO apt-get update -y || true
	else
		LOG "The ondrej/php repo found"
	fi
	PHP_PKG="php8.4"
	if ! apt-cache show "$PHP_PKG" >/dev/null 2>&1; then
		for p in php8.5 php8.3; do
			if apt-cache show "$p" >/dev/null 2>&1; then PHP_PKG="$p"; break; fi
		done
	fi
	LOG "Installing $PHP_PKG and common extensions ..."
	$SUDO apt-get install -y "${PHP_PKG}" "${PHP_PKG}-cli" "${PHP_PKG}-dev" \
		"${PHP_PKG}-xml" "${PHP_PKG}-gmp" "${PHP_PKG}-zip" "${PHP_PKG}-sockets" \
		"${PHP_PKG}-mbstring" "${PHP_PKG}-fileinfo" \
		"${PHP_PKG}-curl" "${PHP_PKG}-intl" || true
}

install_dnf(){
	TOOL="${PKG:-dnf}"
	LOG "$TOOL : installing common packages ..."
	$SUDO $TOOL install -y epel-release curl wget git || true
	$SUDO $TOOL install -y php php-cli php-xml php-gmp php-zip php-mbstring php-fileinfo php-curl php-intl || true
}

install_pacman(){
	LOG "pacman : installing php + common packages"
	$SUDO pacman -Sy --noconfirm php php-pear php-xml php-gmp php-zip php-mbstring php-fileinfo php-curl || true
}

install_apk(){
	LOG "apk : installing php8 + common packages"
	$SUDO apk update || true
	$SUDO apk add --no-cache php8 php8-cli php8-phar php-xml php-gmp php-zip php-mbstring php-fileinfo php-curl php-intl git curl || true
}

install_brew(){
	LOG "Homebrew : installing php"
	if ! command -v brew >/dev/null 2>&1; then
		LOG "Installing Homebrew ..."
		/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)" || true
		eval "$(/opt/homebrew/bin/brew shellenv 2>/dev/null || true)" || true
	fi
	if brew info php@8.4 >/dev/null 2>&1; then
		brew install php@8.4 || true
		brew link --force --overwrite php@8.4 || true
	else
		brew install php || true
	fi
}

case "$PKG" in
	apt) install_apt ;;
	dnf|yum) install_dnf ;;
	pacman) install_pacman ;;
	apk) install_apk ;;
	brew) install_brew ;;
	*) ERR "Unsupported / undetected package manager. Run this on Debian / Ubuntu, RHEL / Fedora, Arch, Alpine, or macOS" ; exit 1 ;;
esac

# verify php #

if ! command -v php >/dev/null 2>&1; then
	ERR "Aborting : php not found after package install"
	exit 1
fi

PHP_BIN="$(command -v php)"
LOG "PHP : $($PHP_BIN -v | head -n1)"
BITS=$($PHP_BIN -r 'echo PHP_INT_SIZE*8;' 2>/dev/null || echo "unknown")
if [ "$BITS" != "64" ]; then
	ERR "Detected PHP integer size : $BITS bits, LiveProto expects 64-bit PHP, Continue at your own risk"
fi

# check extensions ( best-effort ) #

NEEDED=(openssl gmp json xml dom mbstring curl filter hash zlib intl fileinfo zip sockets)
MISSING=()
for e in "${NEEDED[@]}"; do
	if ! php -r "exit(extension_loaded('$e') ? 0 : 1);" >/dev/null 2>&1; then
		MISSING+=("$e")
	fi
done
if [ ${#MISSING[@]} -ne 0 ]; then
	LOG "Missing extensions : ${MISSING[*]} , Try installing distro packages ( eg. php-gmp, php-hash ) and re-run"
else
	LOG "All common extensions present"
fi

# composer installation #

download_and_install_composer() {
	local INSTALLER_URL="https://getcomposer.org/installer"
	local TMP="composer-setup.php"

	LOG "Downloading Composer installer ( curl -> wget -> php copy )"

	if command -v curl >/dev/null 2>&1; then
		curl --fail --silent --show-error --location \
			--connect-timeout 10 --max-time 60 --retry 3 --retry-delay 2 \
			"$INSTALLER_URL" -o "$TMP" || {
				ERR "curl failed to download Composer installer";
				return 1;
			}
	elif command -v wget >/dev/null 2>&1; then
		wget -q --timeout=20 --tries=3 -O "$TMP" "$INSTALLER_URL" || {
			ERR "wget failed to download Composer installer";
			return 1;
		}
	else
		LOG "No curl / wget found -> trying PHP copy() ( requires allow_url_fopen )"
		if php -r 'echo ini_get("allow_url_fopen");' 2>/dev/null | grep -qiE '1|on|true'; then
			php -r "copy('$INSTALLER_URL','$TMP')" || {
				ERR "php copy() failed to download installer";
				return 1;
			}
		else
			ERR "No curl / wget and PHP allow_url_fopen is disabled. Install curl / wget or enable allow_url_fopen"
			return 1
		fi
	fi
	LOG "Running Composer installer ..."
	# Install to /usr/local/bin if possible ( use sudo if needed ), else to $HOME/.local/bin #
	if [ -n "${SUDO:-}" ]; then
		$SUDO php "$TMP" --install-dir=/usr/local/bin --filename=composer || {
			ERR "Composer installer failed ( sudo )";
			rm -f "$TMP";
			return 1;
		}
	elif [ -w /usr/local/bin ]; then
		php "$TMP" --install-dir=/usr/local/bin --filename=composer || {
			ERR "Composer installer failed";
			rm -f "$TMP"
			return 1;
		}
	else
		mkdir -p "$HOME/.local/bin"
		php "$TMP" --install-dir="$HOME/.local/bin" --filename=composer || {
			ERR "Composer installer failed";
			rm -f "$TMP";
			return 1;
		}
		LOG "Composer installed to $HOME/.local/bin. Add it to PATH if necessary: export PATH=\"\$HOME/.local/bin:\$PATH\""
	fi
	rm -f "$TMP"
	LOG "Composer installed as : $(command -v composer || echo '~/.local/bin/composer')"
	return 0
}

download_and_install_composer || exit 1

if ! command -v composer >/dev/null 2>&1; then
	ERR "Composer missing. Aborting composer steps"
	exit 1
fi

# composer require library #

MTPROTO="$HOME/library"
mkdir -p "$MTPROTO"
cd "$MTPROTO"

LOG "Running : composer require taknone/liveproto"
composer require taknone/liveproto || {
	ERR "composer require failed. Inspect output and retry inside $MTPROTO"
}

LOG "PHP : $($PHP_BIN -v | head -n1); Composer : $(composer --version 2>/dev/null || echo 'not found')"
if [ -d "$MTPROTO/vendor/taknone/liveproto" ]; then
	LOG "LiveProto installed at : $MTPROTO/vendor/taknone/liveproto"
else
	ERR "LiveProto not found in vendor ( composer may have failed )"
fi

LOG "Done"

exit 0