# Methods

?> Note, In this section, we only introduced the methods that are for your convenience to work with raw api, and I call them custom methods

---

## update_password()

Updates the account password with a new one or remove it

Usable by :
- [x] Users
- [ ] Bots

> [!NOTE]
> If the value of the parameter `$new` be null , `2FA` will be removed

##### <pre>Arguments</pre>
- password(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Current password ( if required )

- new(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - New password to be set

- hint(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Password hint for recovery

- email(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Recovery email for password reset

##### <pre>Returns</pre>
Bool

##### <pre>Example</pre>
```php
$client->update_password(password : 'oldPass',new : 'newPass',hint : 'MyHint',email : 'user@example.com');
```

---

## send_email_code()

Sends a verification code to the provided email

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- email(<small>string</small>) <kbd style="color : red">required</kbd> :
  - The email address to send the verification code

- email_purpose(<small>EmailPurpose</small>) <kbd onclick = "alert('default : EmailPurpose::LOGINSETUP')">optional</kbd> :
  - The purpose of the email verification request

##### <pre>Returns</pre>
An instance of [SentEmailCode](https://tl.liveproto.dev/#/type/account.SentEmailCode)

##### <pre>Example</pre>
```php
$client->send_email_code(email : 'user@example.com');
```

---

## verify_email()

Verifies an email with the provided code

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- code(<small>string</small>,<small>int</small>) <kbd style="color : red">required</kbd> :
  - The verification code sent to the email

- email_purpose(<small>EmailPurpose</small>) <kbd onclick = "alert('default : EmailPurpose::LOGINSETUP')">optional</kbd> :
  - The purpose for which the email verification is being performed

##### <pre>Returns</pre>
An instance of [EmailVerified](https://tl.liveproto.dev/#/type/account.EmailVerified)

##### <pre>Example</pre>
```php
$client->verify_email(code : 123456);
```

---

## send_code()

Send the confirmation code to the given phone number

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- phone_number(<small>string</small>) <kbd style="color : red">required</kbd> :
  - The phone to which the code will be sent

- ...settings(<small>mixed</small>) <kbd onclick = "alert('default : empty')">optional</kbd> :
  - Any additional parameters you give will be passed to the [codeSettings](https://tl.liveproto.dev/#/constructor/codeSettings) construct

##### <pre>Returns</pre>
An instance of [SentCode](https://tl.liveproto.dev/#/type/auth.SentCode)

##### <pre>Example</pre>
```php
$client->send_code(phone_number : '+1234567890');

$client->send_code(phone_number : '+8884567890', unknown_number : true);
```

---

## sign_in()

Logs in to Telegram to an existing user or bot

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- code(<small>string</small>,<small>int</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - The code that Telegram sent. If you sent it through the application, it will expire immediately

- password(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - 2FA password, required if `SESSION_PASSWORD_NEEDED` exception is raised

- bot_token(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Used to sign in as a bot

- web_token(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Used to sign in via telegram web

- email(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Set email verification

##### <pre>Returns</pre>
An instance of [Authorization](https://tl.liveproto.dev/#/constructor/authorization)

##### <pre>Example</pre>
```php
$client->sign_in(code : 12345);

$client->sign_in(code : 12345,email : 'tak@liveproto.dev');

$client->sign_in(password : 'HelloWorld');

$client->sign_in(bot_token : '4839574812:AAFD39kkdpWt3ywyRZergyOLMaJhac60qc');
```

---

## sign_up()

This method is used to create a new account and your client must be [official](configuration.md#Params)

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- first_name(<small>string</small>) <kbd style="color : red">required</kbd> :
  - Set a first name for your account

- last_name(<small>string</small>) <kbd style="color : red">required</kbd> :
  - Set a last name for your account

##### <pre>Returns</pre>
An instance of [Authorization](https://tl.liveproto.dev/#/constructor/authorization)

##### <pre>Example</pre>
```php
$client->sign_up(first_name : 'Tak', last_name : 'None');
```

---

## resend_code()

Resends the confirmation code

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Returns</pre>
An instance of [SentCode](https://tl.liveproto.dev/#/type/auth.SentCode)

##### <pre>Example</pre>
```php
$client->resend_code();
```

---

## cancel_code()

Cancels the confirmation code

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Returns</pre>
Bool

##### <pre>Example</pre>
```php
$client->cancel_code();
```

---

## reset_login_email()

Reset the login email

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Returns</pre>
Bool

##### <pre>Example</pre>
```php
$client->reset_login_email();
```

---

## firebase_sms()

This method is used to send a code via SMS, and your client must be [official](configuration.md#Params)

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- safety(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Safety net token

- push(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - iOS push secret

##### <pre>Returns</pre>
Bool

##### <pre>Example</pre>
```php
$client->firebase_sms();
```

---

## log_out()

Logs out from the current session

Usable by :
- [x] Users
- [x] Bots

##### <pre>Returns</pre>
An instance of [LoggedOut](https://tl.liveproto.dev/#/constructor/auth.loggedOut)

##### <pre>Example</pre>
```php
$client->log_out();
```

---

## login_token()

Generates a login token

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- except_ids(<small>array&lt;int&gt;</small>) <kbd onclick = "alert('default : array()')">optional</kbd> :
  - List of already logged-in user IDs, to prevent logging in twice with the same user

##### <pre>Returns</pre>
String like `tg://login?token=base64encodedtoken`

##### <pre>Example</pre>
```php
$client->login_token();
```

---

## accept_token()

Accepts a login token embedded in a QR code

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- token(<small>string</small>) <kbd style="color : red">required</kbd> :
  - Login token embedded in QR code

##### <pre>Returns</pre>
An instance of [Authorization](https://tl.liveproto.dev/#/constructor/authorization)

##### <pre>Example</pre>
```php
$client->accept_token(token : 'tg://login?token=base64encodedtoken');
```

---

## wait_token()

Waits for a login token to be accepted

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- except_ids(<small>array&lt;int&gt;</small>) <kbd onclick = "alert('default : array()')">optional</kbd> :
  - List of already logged-in user IDs, to prevent logging in twice with the same user

- timeout(<small>int</small>) <kbd onclick = "alert('default : 30')">optional</kbd> :
  - Set a time out for waiting

##### <pre>Returns</pre>
An instance of [Authorization](https://tl.liveproto.dev/#/constructor/authorization)

##### <pre>Example</pre>
```php
$client->wait_token(timeout : 60);
```

---

## click_button()

Clicks on an inline button within a message

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- message(<small>object</small>) <kbd style="color : red">required</kbd> :
  - The message object containing the button

- i(<small>int</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Row index of the button

- j(<small>int</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Column index of the button

- text(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - The text of the button to click

- data(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Callback data of the button

- query(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Query of the button

- filter(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - A function to filter buttons

- password(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - This is for those buttons that require 2FA

- contact(<small>array</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  -  To share contact

- geo(<small>array</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - To share location

- user(<small>string</small>,<small>int</small>,<small>null</small>,<small>object</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Clicking on the button switch inline must have a destination to send to , as well as the button request peer

##### <pre>Returns</pre>
A mixed response depending on the button type

##### <pre>Example</pre>
```php
$client->click_button(message : $message,i : 0,j : 1);

$client->click_button(message : $message,text : 'Hello');

$client->click_button(message : $message,filter : function(object $button) : bool {
	if(isset($button->text) and str_starts_with($button->text,'X...')){
		/* ✅ Yes, I want to click this button */
		return true;
	} else {
		/* ❌ No, this is not the button I want */
		return false;
	}
});
```

---

## get_dialogs()

Retrieves a list of dialogs ( chats , groups , channels , etc... )

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- offset_peer(<small>string</small>,<small>int</small>,<small>null</small>,<small>object</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Peer used as offset for pagination

- offset(<small>int</small>) <kbd onclick = "alert('default : 0')">optional</kbd> :
  - Numeric offset ( page index ) used for pagination

- offset_id(<small>int</small>) <kbd onclick = "alert('default : 0')">optional</kbd> :
  - Message ID offset used for fetching relative to a specific message

- offset_date(<small>int</small>) <kbd onclick = "alert('default : 0')">optional</kbd> :
  - Date ( unix timestamp ) offset used for pagination

- limit(<small>int</small>) <kbd onclick = "alert('default : 100')">optional</kbd> :
  - Maximum number of dialogs to retrieve in one request / batch

- saved(<small>bool</small>) <kbd onclick = "alert('default : ')">optional</kbd> :
  - If true, the getSavedDialogs or getPinnedSavedDialogs ( pinned = true) method will be used

- hashgen(<small>Closure</small>,<small>array</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional hash generator ( Closure or array of indexes ) used for caching / validation of message sets

- ...args(<small>mixed</small>) <kbd onclick = "alert('default : empty')">optional</kbd> :
  - Any additional variadic parameters passed through to lower-level calls

##### <pre>Returns</pre>
An iterator yielding instances of [Message](https://tl.liveproto.dev/#/type/Message) and one of [Dialog](https://tl.liveproto.dev/#/type/Dialog) , [SavedDialog](https://tl.liveproto.dev/#/type/SavedDialog)

##### <pre>Example</pre>
```php
$dialogs = $client->get_dialogs(offset_peer : '@LiveProto',limit : 50);

$dialogs = $client->get_dialogs(offset_date : strtotime('- 2 days'),pinned : true);

$dialogs = $client->get_dialogs(limit : 90,saved : true);

$dialogs = $client->get_dialogs(limit : 100,saved : true,pinned : true);

/*
 * Now let's look at the results of each of the above models
 * The results of all of them are in the form of an object that has the properties `message` and `dialog`, It's very simple !
 */
foreach($dialogs as $item){
	try {
		echo json_encode($item->message,flags : JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT) , PHP_EOL;
		echo json_encode($item->dialog,flags : JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT) , PHP_EOL;
		echo json_encode($item->peer,flags : JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT) , PHP_EOL;
	} catch(Throwable){
		var_dump($item);
	}
}
```

---

## get_difference()

Fetches update differences ( messages , edits , etc... )

Usable by :
- [x] Users
- [x] Bots

> [!NOTE]
> This method returns a generator , use foreach to iterate

##### <pre>Arguments</pre>
- pts(<small>int</small>) <kbd onclick = "alert('default : 1')">optional</kbd> :
  - The last known PTS (Persistent Timestamp)

- date(<small>int</small>) <kbd onclick = "alert('default : 1')">optional</kbd> :
  - The last known update date

- qts(<small>int</small>) <kbd onclick = "alert('default : 1')">optional</kbd> :
  - The last known QTS (Queue Timestamp)

- total_limit(<small>int</small>,<small>null</small>) <kbd onclick = "alert('default : 0x7fffffff')">optional</kbd> :
  - Simply tells the server to not return the difference if it is bigger than `pts_total_limit`, If the remote pts is too big

- pts_limit(<small>int</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Simply tells the server to not return the difference if it is bigger than `pts_total_limit`, If the remote pts is too big

- qts_limit(<small>int</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Simply tells the server to not return the difference if it is bigger than `pts_total_limit`, If the remote pts is too big

- deep(<small>bool</small>) <kbd onclick = "alert('default : false')">optional</kbd> :
  - Whether to perform a deep fetch when differences are too long

##### <pre>Returns</pre>
An array containing the difference updates

##### <pre>Example</pre>
```php
foreach($client->get_difference() as $difference){
	var_dump($difference);
}
```

---

## get_channel_difference()

Retrieves incremental updates ( messages , edits , and other events ) for a specific channel

Usable by :
- [x] Users
- [x] Bots

> [!NOTE]
> This method returns a generator , use foreach to iterate

##### <pre>Arguments</pre>
- channel(<small>int</small>,<small>string</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - Identifier of the channel can be a channel ID , username string , or Channel object

- filter(<small>object</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - The filter must be of type `ChannelMessagesFilter`

- pts(<small>int</small>) <kbd onclick = "alert('default : 1')">optional</kbd> :
  - Current pts state for this channel , use the last received pts to continue from where you left off

- limit(<small>int</small>) <kbd onclick = "alert('default : 0x7fffffff')">optional</kbd> :
  - Maximum number of updates to fetch per request ( Telegram may enforce its own limits )

##### <pre>Returns</pre>
A generator yielding `Updates` objects ( containing messages , other updates , users , chats ) until no new updates are available

##### <pre>Example</pre>
```php
foreach($client->get_channel_difference('@LiveProto') as $channelDifference){
	var_dump($channelDifference);
}
```

---

## inputify_file_location()

Used to get input of any file location from media

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- media(<small>object</small>) <kbd style="color : red">required</kbd> :
  - The media can be photos, documents, or even games, etc

- thumb_size(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Thumbnail size to download the thumbnail

##### <pre>Returns</pre>
An instance of [InputFileLocation](https://tl.liveproto.dev/#/type/InputFileLocation)

##### <pre>Example</pre>
```php
$stickerSet = $client->inputStickerSetShortName(short_name : 'LiveProto'); // like : https://t.me/addemoji/LiveProto
$stickers = $client->messages->getStickerSet(stickerset : $stickerSet,hash : 0);
$document = $stickers->documents[0]; // the first emoji / sticker

$inputFileLocation = $client->inputify_file_location(media : $document);
```

---

## perform_download()

Performs a download according to the requested transfer kind. Delegates to file, stream or browser download handlers and returns a result specific to the transfer kind

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- destination(<small>mixed</small>) <kbd style="color : red">required</kbd> :
  - Target for the download. For TransferKind::FILE and TransferKind::BROWSER this is treated as a path and HTTP status code, For TransferKind::STREAM this may be ignored and a generator is returned instead

- size(<small>int</small>) <kbd style="color : red">required</kbd> :
  - Total size of the file in bytes ( or negative / zero when unknown / streaming )

- dc_id(<small>int</small>) <kbd style="color : red">required</kbd> :
  - Data center ID where the file is stored ( used to switch connections )

- location(<small>InputFileLocation</small>) <kbd style="color : red">required</kbd> :
  - An [InputFileLocation](https://tl.liveproto.dev/#/type/InputFileLocation) object pointing to the remote file on Telegram

- progresscallback(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional callback invoked with progress percentage ( float ). Return false ( or callback that results in false ) to cancel in some implementations

- key(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional decryption key used when file bytes are encrypted ( secret or CDN encrypted files )

- iv(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional initialization vector used for decryption

- transfer_kind(<small>TransferKind</small>) <kbd onclick = "alert('default : TransferKind::FILE')">optional</kbd> :
  - Determines how the file is transferred: TransferKind::FILE ( save to local file ), TransferKind::STREAM ( yield chunks ) or TransferKind::BROWSER ( stream as HTTP download )

- mime_type(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional MIME type used to set/guess file extension or set Content-Type for browser downloads

##### <pre>Returns</pre>
Returns the local path ( string ) for TransferKind::FILE, a [Generator](https://www.php.net/manual/en/class.generator) when TransferKind::STREAM, or an HTTP status integer (e.g. 200/206) for TransferKind::BROWSER

##### <pre>Example</pre>
```php
$inputFileLocation = $client->inputify_file_location(media : $document);

$client->perform_download(destination : '/tmp/out.bin',size : 0x100000000,dc_id : 2,location : $inputFileLocation);
```

---

## apply_raw_buffer()

Applies a raw byte buffer to the requested transfer kind : writes bytes to a file, yields bytes for a stream, or sends an HTTP download response for browser transfers

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- destination(<small>mixed</small>) <kbd style="color : red">required</kbd> :
  - Destination path (string) or other receiver depending on transfer kind. If a directory is provided a filename will be generated

- bytes(<small>string</small>) <kbd style="color : red">required</kbd> :
  - Raw bytes that will be written, yielded or emitted to the client

- progresscallback(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional callback that is invoked with 100. If the callback returns / awaits false the operation is treated as canceled

- key(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional decryption key, if provided bytes will be decrypted before being applied

- iv(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional decryption IV used together with key

- transfer_kind(<small>TransferKind</small>) <kbd onclick = "alert('default : TransferKind::FILE')">optional</kbd> :
  - How to handle the bytes: FILE ( save ), STREAM ( yield ), or BROWSER ( send HTTP response )

- mime_type(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional MIME type that may be used to determine a file extension for saved files or Content-Type for browser

##### <pre>Returns</pre>
For TransferKind::FILE returns the string path to the saved file; for TransferKind::STREAM yields bytes (Generator); for TransferKind::BROWSER returns an HTTP status int or null (if HEAD or canceled)

##### <pre>Example</pre>
```php
$bytes = random_bytes(0xa00000);

$client->apply_raw_buffer(destination : './save.bin',bytes : $bytes);
```

---

## download_chunks()

Downloads a file in chunks from Telegram ( supports CDN and parallel requests ). Yields byte-chunks keyed by their offset. Designed for high-performance downloads with multiple connections and CDN handling

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- size(<small>int</small>) <kbd style="color : red">required</kbd> :
  - Total file size in bytes ( or negative / zero for unknown / streamed sizes )

- dc_id(<small>int</small>) <kbd style="color : red">required</kbd> :
  - Data center id where the media is stored

- location(<small>InputFileLocation</small>) <kbd style="color : red">required</kbd> :
  - An [InputFileLocation](https://tl.liveproto.dev/#/type/InputFileLocation) object pointing to the remote file on Telegram

- progresscallback(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional callback to receive progress percentage ( float )

- key(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional decryption key used when chunks are encrypted ( CDN or secret files )

- iv(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional decryption IV used together with key

- offset(<small>int</small>) <kbd onclick = "alert('default : 0')">optional</kbd> :
  - Byte offset to start downloading from

- limit(<small>int</small>) <kbd onclick = "alert('default : 1024')">optional</kbd> :
  - Maximum chunk size ( in KB ) to request per call before alignment

##### <pre>Returns</pre>
An instance of [Generator](https://www.php.net/manual/en/class.generator) yielding byte strings keyed by their offset ( int => string )

##### <pre>Example</pre>
```php
$inputFileLocation = $client->inputify_file_location(media : $document);

foreach($client->download_chunks(size : 0x100000000,dc_id : 2,location : $inputFileLocation) as $offset => $bytes){
	// write $bytes at $offset //
}
```

---

## download_browser()

Streams a file to the HTTP client honoring Range requests. Sends proper HTTP headers and prints byte buffers directly. Returns the HTTP status code ( 200 or 206 , etc ) or null on HEAD / cancel / error

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- path(<small>string</small>) <kbd style="color : red">required</kbd> :
  - Filename presented to the browser ( or empty to generate a name from the file location )

- mime_type(<small>string</small>) <kbd style="color : red">required</kbd> :
  - MIME type used to set Content-Type header and determine file extension

- size(<small>int</small>) <kbd style="color : red">required</kbd> :
  - Total size of the remote file in bytes

- dc_id(<small>int</small>) <kbd style="color : red">required</kbd> :
  - Data center id for the file

- location(<small>InputFileLocation</small>) <kbd style="color : red">required</kbd> :
  - Remote input location for the file

- progresscallback(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional progress callback for streaming progress updates

- key(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional decryption key for encrypted chunks

- iv(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional decryption IV

##### <pre>Returns</pre>
An integer HTTP status code ( 200 or 206 ) when bytes were sent, or null on HEAD requests or if the download was canceled / failed

##### <pre>Example</pre>
```php
$status = $client->download_browser(path : 'compressed',mime_type : 'application/zip',size : 100 * 1024 * 1024,dc_id : 4,location : $location);

if($status === 206){
	/* partial content was served */
}
```

---

## download_file()

Downloads a full file to disk by consuming the download_chunks generator and writing chunks to a local file. Ensures file extension is set when possible

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- path(<small>string</small>) <kbd style="color : red">required</kbd> :
  - Destination file path ( may be a directory - a filename will be generated )

- mime_type(<small>string</small>) <kbd style="color : red">required</kbd> :
  - MIME type used to infer a file extension if none is present

- size(<small>int</small>) <kbd style="color : red">required</kbd> :
  - Total size of the remote file in bytes

- dc_id(<small>int</small>) <kbd style="color : red">required</kbd> :
  - Data center id where the file is stored

- location(<small>InputFileLocation</small>) <kbd style="color : red">required</kbd> :
  - Remote file location object

- progresscallback(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional progress callback invoked with percentage values while downloading and writing chunks

- key(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional decryption key for encrypted downloads

- iv(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional decryption IV

##### <pre>Returns</pre>
A string containing the final local path of the downloaded file ( may include newly-appended extension )

##### <pre>Example</pre>
```php
$peer = $client->get_input_peer('@LiveProto');
$photo_id = $client->get_peer($peer)->photo->photo_id;
$location = $client->inputPeerPhotoFileLocation(peer : $peer,photo_id : $photo_id,big : true);

$client->download_file(path : __DIR__.DIRECTORY_SEPARATOR.'file',mime_type : 'image/jpeg',size : 2 * 1024 * 1024,dc_id : 3,location : $location);
```

---

## download_photo()

Downloads a photo media object

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- to(<small>mixed</small>) <kbd style="color : red">required</kbd> :
  - Alias of destination, defining where the transferred data should be written or delivered

- file(<small>object</small>) <kbd style="color : red">required</kbd> :
  - Photo or message media photo object

- big(<small>bool</small>) <kbd onclick = "alert('default : true')">optional</kbd> :
  - Whether to download the big size

- progresscallback(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Callback for download progress

- key(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Encryption key ( if required )

- iv(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Initialization vector ( if required )

- transfer_kind(<small>TransferKind</small>) <kbd onclick = "alert('default : TransferKind::FILE')">optional</kbd> :
  - Defines how the downloaded data should be delivered ( saved to a file, streamed, or sent directly to the browser )

##### <pre>Returns</pre>
The result of the transfer, varying by transfer kind ( path, generator, or HTTP status code )

##### <pre>Example</pre>
```php
$full = $client->get_full_peer('@LiveProto');

$client->download_photo(to : './file.jpg',file : $full->chat_photo);
```

---

## download_profile_photo()

Downloads a profile photo from user , chat , or channel

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- to(<small>mixed</small>) <kbd style="color : red">required</kbd> :
  - Alias of destination, defining where the transferred data should be written or delivered

- file(<small>object</small>) <kbd style="color : red">required</kbd> :
  - User, Chat, or Channel object containing a profile photo

- big(<small>bool</small>) <kbd onclick = "alert('default : true')">optional</kbd> :
  - Whether to download the big size

- progresscallback(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Callback for download progress

- key(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Decryption key ( if required )

- iv(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Initialization vector for decryption ( if required )

- transfer_kind(<small>TransferKind</small>) <kbd onclick = "alert('default : TransferKind::FILE')">optional</kbd> :
  - Defines how the downloaded data should be delivered ( saved to a file, streamed, or sent directly to the browser )

##### <pre>Returns</pre>
The result of the transfer, varying by transfer kind ( path, generator, or HTTP status code )

##### <pre>Example</pre>
```php
$peer = $client->get_peer('@LiveProto');

$client->download_profile_photo(to : './file.jpeg',file : $peer);
```

---

## download_document()

Downloads a document or its thumbnail

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- to(<small>mixed</small>) <kbd style="color : red">required</kbd> :
  - Alias of destination, defining where the transferred data should be written or delivered

- file(<small>object</small>) <kbd style="color : red">required</kbd> :
  - Document or message media document object

- thumb(<small>bool</small>) <kbd onclick = "alert('default : false')">optional</kbd> :
  - Whether to download the document thumbnail instead of full document

- big(<small>bool</small>) <kbd onclick = "alert('default : true')">optional</kbd> :
  - Whether to download the big size

- progresscallback(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Callback for download progress

- key(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Decryption key ( if required )

- iv(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Initialization vector for decryption ( if required )

- transfer_kind(<small>TransferKind</small>) <kbd onclick = "alert('default : TransferKind::FILE')">optional</kbd> :
  - Defines how the downloaded data should be delivered ( saved to a file, streamed, or sent directly to the browser )

##### <pre>Returns</pre>
The result of the transfer, varying by transfer kind ( path, generator, or HTTP status code )

##### <pre>Example</pre>
```php
$stickerSet = $client->inputStickerSetShortName(short_name : 'LiveProto'); // like : https://t.me/addemoji/LiveProto
$stickers = $client->messages->getStickerSet(stickerset : $stickerSet,hash : 0);
$document = $stickers->documents[0]; // the first emoji / sticker

$client->download_document(to : './file.tgs',file : $document);
```

---

## download_web_document()

Downloads a web document from its URL

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- to(<small>mixed</small>) <kbd style="color : red">required</kbd> :
  - Alias of destination, defining where the transferred data should be written or delivered

- file(<small>object</small>) <kbd style="color : red">required</kbd> :
  - WebDocument object containing URL and metadata

- progresscallback(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Callback for download progress

- key(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Decryption key ( if required )

- iv(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Initialization vector for decryption ( if required )

- transfer_kind(<small>TransferKind</small>) <kbd onclick = "alert('default : TransferKind::FILE')">optional</kbd> :
  - Defines how the downloaded data should be delivered ( saved to a file, streamed, or sent directly to the browser )

##### <pre>Returns</pre>
The result of the transfer, varying by transfer kind ( path, generator, or HTTP status code )

##### <pre>Example</pre>
```php
$peer = $client->get_input_peer('me');
$status = $client->payments->getStarsStatus(peer : $peer);
if($status->subscriptions){
	$subscription = $status->subscriptions[0];
	if($subscription->photo){
		$client->download_web_document(to : './file.unknown',file : $subscription->photo);
	}
}
```

---

## download_contact()

Downloads contact information as a vCard

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- to(<small>mixed</small>) <kbd style="color : red">required</kbd> :
  - Alias of destination, defining where the transferred data should be written or delivered

- file(<small>object</small>) <kbd style="color : red">required</kbd> :
  - Media contact object containing vCard

- progresscallback(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Callback for download progress

- key(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Decryption key ( if required )

- iv(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Initialization vector for decryption ( if required )

- transfer_kind(<small>TransferKind</small>) <kbd onclick = "alert('default : TransferKind::FILE')">optional</kbd> :
  - Defines how the downloaded data should be delivered ( saved to a file, streamed, or sent directly to the browser )

##### <pre>Returns</pre>
The result of the transfer, varying by transfer kind ( path, generator, or HTTP status code )

##### <pre>Example</pre>
```php
$mediaContact = $client->inputMediaContact(phone_number : '+123456789',first_name : 'Live',last_name : 'Proto',vcard : strval(null));

$client->download_contact(to : './file.vcard',file : $mediaContact);
```

---

## download_secret_file()

Downloads and decrypts a secret / encrypted file

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- to(<small>mixed</small>) <kbd style="color : red">required</kbd> :
  - Alias of destination, defining where the transferred data should be written or delivered

- file(<small>object</small>) <kbd style="color : red">required</kbd> :
  - Encrypted file object or decrypted message object

- progresscallback(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Callback for download progress

- key(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Encryption key for decryption

- iv(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Encryption iv for decryption

- transfer_kind(<small>TransferKind</small>) <kbd onclick = "alert('default : TransferKind::FILE')">optional</kbd> :
  - Defines how the downloaded data should be delivered ( saved to a file, streamed, or sent directly to the browser )

##### <pre>Returns</pre>
The result of the transfer, varying by transfer kind ( path, generator, or HTTP status code )

##### <pre>Example</pre>
```php
$encryptedMessage = $client->fetchUpdate(array('updateNewEncryptedMessage','updateEncryption'),callback : fn(object $update) : bool => $update->message->file instanceof \Tak\Liveproto\Tl\Types\Other\EncryptedFile,timeout : 100)->await();

$path = $client->download_secret_file(to : './file',file : $encryptedMessage);

/* If you have not chosen any extension for your file, we will choose one for you and include it in the output */
echo 'Path : ' , $path , PHP_EOL;
```

---

## download_media()

Automatically downloads the correct media type ( photo , document , ... )

Usable by :
- [x] Users
- [x] Bots

> [!NOTE]
> I suggest you only use other download methods when necessary. The easiest and best way is to use this method

##### <pre>Arguments</pre>
- destination(<small>mixed</small>) <kbd style="color : red">required</kbd> :
  - Target output for the transfer. Its meaning depends on the selected `TransferKind`

- media(<small>object</small>) <kbd style="color : red">required</kbd> :
  - Media object ( photo , document , contact , ... )

- thumb(<small>bool</small>) <kbd onclick = "alert('default : false')">optional</kbd> :
  - Whether to download the document thumbnail instead of full document

- big(<small>bool</small>) <kbd onclick = "alert('default : true')">optional</kbd> :
  - Whether to download the big size

- progresscallback(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Callback for download progress

- key(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Decryption key ( if required )

- iv(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Decryption iv ( if required )

- transfer_kind(<small>TransferKind</small>) <kbd onclick = "alert('default : TransferKind::FILE')">optional</kbd> :
  - Defines how the downloaded data should be delivered ( saved to a file, streamed, or sent directly to the browser )

##### <pre>Returns</pre>
The result of the transfer, varying by transfer kind ( path, generator, or HTTP status code )

##### <pre>Example</pre>
```php
$stickerSet = $client->inputStickerSetDice(emoticon : '🎯');
$stickers = $client->messages->getStickerSet(stickerset : $stickerSet,hash : 0);
$document = $stickers->documents[array_rand($stickers->documents)];

$client->download_media(destination : './file.tgs',media : $document,progresscallback : function(float $percentage) : mixed {
	echo $percentage , '%' , PHP_EOL;
	return true; // If you return false, the download will stop
});
```

---

## markdown()

Parses a Markdown‑formatted string into a Telegram text + entities array

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- text(<small>string</small>) <kbd style="color : red">required</kbd> :
  - Your Markdown‑formatted text

##### <pre>Returns</pre>
An array with `text` and `entities` parsed from Markdown

##### <pre>Example</pre>
```php
list($text,$entities) = $client->markdown('
Thank you for using the [LiveProto 🌱](https://t.me/LiveProto) library

\```php
print \'I ❤️ LiveProto\';
\```

~~Strike~~

__Underline__

"Blockquote"

**Bold**

_Italic_

`Code`

||Spoiler||
');

$peer = $client->get_input_peer('@TakNone');
$client->messages->sendMessage(peer : $peer,message : $text,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),entities : $entities);
```

---

## markdown_escape()

Escape string for Markdown‑formatted

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- text(<small>string</small>) <kbd style="color : red">required</kbd> :
  - Your Markdown‑formatted text

##### <pre>Returns</pre>
An escaped string

##### <pre>Example</pre>
```php
$escaped = $client->markdown_escape('~~Strike~~');

list($text,$entities) = $client->markdown('How do we create a string like ~~Strike~~ ? Well, like '.$escaped);

$peer = $client->get_input_peer('@TakNone');
$client->messages->sendMessage(peer : $peer,message : $text,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),entities : $entities);
```

---

## html()

Parses an HTML‑formatted string into a Telegram text + entities array

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- text(<small>string</small>) <kbd style="color : red">required</kbd> :
  - Your HTML‑formatted text

##### <pre>Returns</pre>
An array with `text` and `entities` parsed from HTML

##### <pre>Example</pre>
```php
list($text,$entities) = $client->html('
Thank you for using the <a href = "https://t.me/LiveProto">LiveProto 🌱</a> library

<b>bold</b>, <strong>bold</strong>

<i>italic</i>, <em>italic</em>

<u>underline</u>, <ins>underline</ins>

<s>strikethrough</s>, <strike>strikethrough</strike>, <del>strikethrough</del>

<span class = "tg-spoiler">spoiler</span>, <tg-spoiler>spoiler</tg-spoiler>

<b>bold <i>italic bold <s>italic bold strikethrough <span class = "tg-spoiler">italic bold strikethrough spoiler</span></s> <u>underline italic bold</u></i> bold</b>

<a href = "http://www.example.com/">inline URL</a>

<a href = "tg://user?id=123456789">inline mention of a user</a>

<tg-emoji emoji-id = "5820916017458583465">🌱</tg-emoji>

<code>inline fixed-width code</code>

<pre>pre-formatted fixed-width code block</pre>

<pre><code class = "language-python">pre-formatted fixed-width code block written in the Python programming language</code></pre>

<blockquote>
Block quotation started
Block quotation continued
The last line of the block quotation
</blockquote>

<blockquote expandable>
Expandable block quotation started
Expandable block quotation continued
Expandable block quotation continued
Hidden by default part of the block quotation started
Expandable block quotation continued
The last line of the block quotation
</blockquote>
');

$peer = $client->get_input_peer('@TakNone');
$client->messages->sendMessage(peer : $peer,message : $text,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),entities : $entities);
```

---

## html_escape()

Escape string for HTML‑formatted

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- text(<small>string</small>) <kbd style="color : red">required</kbd> :
  - Your HTML‑formatted text

##### <pre>Returns</pre>
An escaped string

##### <pre>Example</pre>
```php
$escaped = $client->html_escape('<s>strikethrough</s>');

list($text,$entities) = $client->html('How do we create a string like <s>strikethrough</s> ? Well, like '.$escaped);

$peer = $client->get_input_peer('@TakNone');
$client->messages->sendMessage(peer : $peer,message : $text,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),entities : $entities);
```

---

## format_entities()

Formats a plain string using a given set of message entities

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- text(<small>string</small>) <kbd style="color : red">required</kbd> :
  - The plain text to format

- entities(<small>array</small>) <kbd style="color : red">required</kbd> :
  - An array of `MessageEntity` objects / descriptors

##### <pre>Returns</pre>
An array with the formatted `text` and adjusted `entities`

##### <pre>Example</pre>
```php
list($text,$entities) = $client->html('Thank you for using the <a href = "https://t.me/s/LiveProto">LiveProto 🌱</a> library');

$formatted = $client->format_entities(text : $text,entities : $entities);

foreach($formatted as $newEntity){
	echo 'Text : ' , $newEntity->text , PHP_EOL;
	if(isset($newEntity->url)){
		var_dump($newEntity->open());
	}
}
```

---

## get_input_media()

Used to get input of media from message media

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- message_media(<small>object</small>) <kbd style="color : red">required</kbd> :
  - An object of type [MessageMedia](https://tl.liveproto.dev/#/type/MessageMedia) must be

##### <pre>Returns</pre>
An instance of [InputMedia](https://tl.liveproto.dev/#/type/InputMedia)

##### <pre>Example</pre>
```php
$messageMedia = $client->messageMediaDice(value : 6,emoticon : '🎲');

$inputMedia = $client->get_input_media($messageMedia);

$peer = $client->get_input_peer('@TakNone');

$caption = 'Hello World 🌎';

$client->messages->sendMedia(peer : $peer,media : $inputMedia,message : $caption,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX));
```

---

## inputify_media()

Used to get input of any media from media

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- media(<small>object</small>) <kbd style="color : red">required</kbd> :
  - The media can be photos, documents, or even games, etc

##### <pre>Returns</pre>
An instance of [InputPhoto](https://tl.liveproto.dev/#/type/InputPhoto), [inputDocument](https://tl.liveproto.dev/#/type/inputDocument), [inputGeoPoint](https://tl.liveproto.dev/#/type/inputGeoPoint) , etc

##### <pre>Example</pre>
```php
$photo = $client->photoEmpty(id : 123456789);

$inputPhoto = $client->inputify_media(media : $photo);
```

---

## get_message_media()

Used to get message media from input media uploaded

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- inputMedia(<small>object</small>) <kbd style="color : red">required</kbd> :
  - The media can be photos, documents, or even games, etc

- peer(<small>string</small>,<small>int</small>,<small>object</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Username, user ID, or object representing the peer

- ...args(<small>mixed</small>) <kbd onclick = "alert('default : empty')">optional</kbd> :
  - Any additional parameters you give will be passed to the [uploadMedia](https://tl.liveproto.dev/#/method/messages.uploadMedia)

##### <pre>Returns</pre>
An instance of [MessageMedia](https://tl.liveproto.dev/#/type/MessageMedia)

##### <pre>Example</pre>
```php
$inputFile = $client->upload_file(path : './file.png');

$inputMedia = $client->inputMediaUploadedPhoto(file : $inputFile);

$messageMedia = $client->get_message_media(inputMedia : $inputMedia);
```

---

## from_file_id()

Parses a Telegram Bot API-style file_id and converts it into an internal file representation

Usable by :
- [x] Users
- [x] Bots

> [!NOTE]
> In the result, the closure `download` , it takes parameters ( `$destination` , `$progresscallback` , `$key` , `$iv` , `$transfer_kind` , `$mime_type` ) that are passed to the [perform_download](en/functions.md#perform_download) method

##### <pre>Arguments</pre>
- file_id(<small>string</small>) <kbd style="color : red">required</kbd> :
  - The file_id string received from the Bot API (e.g., in a message)

##### <pre>Returns</pre>
An object representing the decoded internal file reference (e.g., location, ID, type)

##### <pre>Example</pre>
```php
$fileObject = $client->fromBotAPI('AgACAgUAAxkBAA..');

echo 'File type : ' , $fileObject->file_type , PHP_EOL;
echo 'File data center id : ' , $fileObject->dc_id , PHP_EOL;

$realpath = $fileObject->download(destination : './file');

echo 'File downloaded in : ' , $realpath;
```

---

## to_file_id()

Encodes an internal MTProto file reference into a Telegram Bot API‐style `file_id` string

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- file_type(<small>FileType</small>) <kbd style="color : red">required</kbd> :
  - The enumerated file ID type ( e.g. Photo , Document ) as [`FileType`](en/enums.md#FileType)

- dc_id(<small>int</small>) <kbd style="color : red">required</kbd> :
  - Data center ID where the file is stored

- input_location(<small>object</small>) <kbd style="color : red">required</kbd> :
  - Internal location object ( e.g. `InputDocumentFileLocation` , `inputWebDocument` , ... ) containing identifiers like `volume_id` , `local_id` , `secret` , `url` parameters

- version(<small>int</small>) <kbd onclick = "alert('default : 4')">optional</kbd> :
  - MTProto file_id version , must match the protocol version expected by Bot API

- sub_version(<small>int</small>) <kbd onclick = "alert('default : 54')">optional</kbd> :
  - MTProto file_id sub-version or class identifier , used internally by Telegram

##### <pre>Returns</pre>
A base64 URL-safe string representing the Bot API `file_id`

##### <pre>Example</pre>
```php
$file_id = 'AgACAgUAAxkBAA..';

$fileObject = $client->fromBotAPI($file_id);

$generated = $client->toBotAPI($fileObject->file_type,$fileObject->dc_id,$fileObject->input_location,$fileObject->version,$fileObject->sub_version);

var_dump($file_id === $generated);
```

---

## inline_query()

Executes an inline bot query and returns results

Usable by :
- [x] Users
- [ ] Bots

> [!NOTE]
> In the result , the closure `click` , it also takes additional parameters (mixed ...$args) that are passed to the [click_inline](en/functions.md#click_inline) method

##### <pre>Arguments</pre>
- bot(<small>string</small>,<small>int</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - Username , ID , or bot entity to query

- query(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - The inline query string

- offset(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Offset for paginated results

- peer(<small>string</small>,<small>int</small>,<small>object</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Peer (chat) where results will be sent

- geo_point(<small>object</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional geo-location for the query

##### <pre>Returns</pre>
An instance of [BotResults](https://tl.liveproto.dev/#/type/messages.BotResults) containing inline query results,with a `click` helper

##### <pre>Example</pre>
```php
$results = $client->inline_query(bot : '@like',query : 'Do you enjoy working with LiveProto ?');

$resultSent = $results->click(index : 0); // Clicks the first result

$resultSent =  $results->click(type : 'article'); // Click on the first result with the type article

$resultSent = $results->click(type : 'article',index : 1); // Click on the second result with the type article

$resultSent->click(text : '👍'); // Clicking on the buttons of the result that has been sent
```

---

## get_prepared_inline_message()

Retrieves a prepared inline message result for a bot and lets you send it into a chat via its `click` helper

Usable by :
- [x] Users
- [ ] Bots

> [!NOTE]
> In the result , the closure `click` , it also takes additional parameters (mixed ...$args) that are passed to the [click_inline](en/functions.md#click_inline) method

##### <pre>Arguments</pre>
- bot(<small>string</small>,<small>int</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - Bot username , ID or bot entity whose inline results you want

- id(<small>string</small>) <kbd style="color : red">required</kbd> :
  - The ID of the inline result has been prepared [PreparedInlineMessage](https://tl.liveproto.dev/#/bots/api#preparedinlinemessage)

##### <pre>Returns</pre>
An instance of [PreparedInlineMessage](https://tl.liveproto.dev/#/constructor/messages.PreparedInlineMessage) with a `click` closure

##### <pre>Example</pre>
```php
$result = $client->get_prepared_inline_message(bot : '@like',id : 'some-result‑id');

$result->click(peer : '@LiveProtoChat');
```

---

## click_inline()

Clicks ( sends ) a chosen inline result into a chat

Usable by :
- [x] Users
- [ ] Bots

> [!NOTE]
> It is better not to use this function directly because its closure is intended for the output of methods related to inline queries. In the result, the closure `click` , it also takes additional parameters (mixed ...$args) that are passed to the [click_button](en/functions.md#click_button) method

##### <pre>Arguments</pre>
- query_id(<small>int</small>) <kbd style="color : red">required</kbd> :
  - ID of the inline query session

- id(<small>string</small>) <kbd style="color : red">required</kbd> :
  - ID of the result to send

- peer(<small>string</small>,<small>int</small>,<small>object</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Target peer ( chat ) where the result will be sent

- ...args(<small>mixed</small>) <kbd onclick = "alert('default : empty')">optional</kbd> :
  - Any additional parameters you give will be passed to the [sendInlineBotResult](https://tl.liveproto.dev/#/method/messages.sendInlineBotResult)

##### <pre>Returns</pre>
A Update , potentially with a `click` closure for click on buttons

##### <pre>Example</pre>
```php
$result = $client->click_inline(query_id : 123456789,id : 'result-id',peer : '@LiveProtoChat');

$result->click(text : '👍'); // Clicking on the buttons of the result that has been sent
```

---

## get_input_peer()

Returns the input peer object ( InputPeerUser , InputPeerChat , etc. ) for the given peer identifier

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- peer(<small>string</small>,<small>int</small>,<small>null</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - Username, user ID, or object representing the peer

- hash(<small>int</small>) <kbd onclick = "alert('default : 0')">optional</kbd> :
  - Hash value used to fetch specific peer state ( usually not needed )

##### <pre>Returns</pre>
An instance of [InputPeer](https://tl.liveproto.dev/#/type/InputPeer)

##### <pre>Example</pre>
```php
$inputPeer = $client->get_input_peer(null); // inputPeerEmpty //

$inputPeer = $client->get_input_peer('me'); // inputPeerSelf //
$inputPeer = $client->get_input_peer('bot'); // inputPeerSelf //

$inputPeer = $client->get_input_peer('@username'); // inputPeerChannel //
$inputPeer = $client->get_input_peer('+42777'); // inputPeerUser //

$inputPeer = $client->get_input_peer(777000); // inputPeerUser //
```

---

## get_input_peer_from_message()

Defines a min peer that was seen in a certain message of a certain chat

Usable by :
- [ ] Users
- [x] Bots

##### <pre>Arguments</pre>
- message(<small>object</small>) <kbd style="color : red">required</kbd> :
  - The message object an instance of [Message](https://core.telegram.org/constructor/message)

##### <pre>Returns</pre>
An instance of [InputPeer](https://tl.liveproto.dev/#/type/InputPeer)

##### <pre>Example</pre>
```php
$inputPeer = $client->get_input_peer_from_message(message : $message);
```

---

## get_peer()

Returns the full resolved peer information (user, chat, or channel) for the given peer input

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- peer(<small>string</small>,<small>int</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - Username,user ID,or peer object

##### <pre>Returns</pre>
An instance of [User](https://tl.liveproto.dev/#/constructor/user), [Chat](https://tl.liveproto.dev/#/constructor/chat), or [Channel](https://tl.liveproto.dev/#/constructor/channel)

##### <pre>Example</pre>
```php
$inputPeer = $client->get_peer('@example_user');
```

---

## get_full_peer()

Retrieves the complete peer info including full profile details and settings

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- peer(<small>string</small>,<small>int</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - Peer to fetch full info for ( username, ID, or object )

##### <pre>Returns</pre>
An instance of [UserFull](https://tl.liveproto.dev/#/constructor/userFull) or [ChatFull](https://tl.liveproto.dev/#/constructor/chatFull) or [ChannelFull](https://tl.liveproto.dev/#/constructor/channelFull)

##### <pre>Example</pre>
```php
var_dump($client->get_full_peer('@LiveProto'));
```

---

## get_peer_id()

Extracts the unique identifier ( ID ) from a peer input

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- peer(<small>string</small>,<small>int</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - Peer input (ID,username,or object)

##### <pre>Returns</pre>
An integer representing the Telegram user/chat/channel ID

##### <pre>Example</pre>
```php
var_dump($client->get_peer_id('@LiveProto'));
```

---

## get_peer_type()

Returns the peer type ( user or chat or channel) based on the input

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- peer(<small>string</small>,<small>int</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - Target peer to analyze

##### <pre>Returns</pre>
An instance of enum [`PeerType`](en/enums.md#PeerType)

##### <pre>Example</pre>
```php
var_dump($client->get_peer_type('@LiveProto')); // enum(Tak\Liveproto\Enums\PeerType::CHANNEL)
```

---

## send_secret_message()

Sends a text message in an end‐to‐end encrypted secret chat with a self‐destruct timer

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- peer(<small>string</small>,<small>int</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - The recipient of the secret message ( user ID, username, or user object )

- message(<small>string</small>) <kbd style="color : red">required</kbd> :
  - Text content to send in the secret chat

- ttl(<small>int</small>) <kbd style="color : red">required</kbd> :
  - Time‐to‐live (self‑destruct) for the message, in seconds

- ...arguments(<small>mixed</small>) <kbd onclick = "alert('default : empty')">optional</kbd> :
  - Any additional parameters you give will be passed to the [decryptedMessage](https://tl.liveproto.dev/#/constructor/decryptedMessage)

##### <pre>Returns</pre>
An instance of [SentEncryptedMessage](https://tl.liveproto.dev/#/type/messages.SentEncryptedMessage)

##### <pre>Example</pre>
```php
$peer = $client->get_input_peer('@TakNone');

$client->send_secret_message(peer : $peer,message : 'This will vanish soon!',ttl : 10);
```

---

## send_secret_file()

Sends a file with a caption in a secret chat , encrypted end‑to‑end

Usable by :
- [x] Users
- [ ] Bots

> [!NOTE]
> Please use method [send_secret_media](en/functions.md#send_secret_media) instead

##### <pre>Arguments</pre>
- peer(<small>string</small>,<small>int</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - The recipient of the file ( user ID, username, or user object )

- file(<small>object</small>) <kbd style="color : red">required</kbd> :
  - The file object to send (e.g., InputEncryptedFile)

- message(<small>string</small>) <kbd style="color : red">required</kbd> :
  - Caption or description for the file

- ttl(<small>int</small>) <kbd style="color : red">required</kbd> :
  - Time in seconds before the message self‑destructs after viewing

- ...arguments(<small>mixed</small>) <kbd onclick = "alert('default : empty')">optional</kbd> :
  - Any additional parameters you give will be passed to the [decryptedMessage](https://tl.liveproto.dev/#/constructor/decryptedMessage)

##### <pre>Returns</pre>
An instance of [SentEncryptedMessage](https://tl.liveproto.dev/#/type/messages.SentEncryptedMessage)

##### <pre>Example</pre>
```php
$peer = $client->get_input_peer('@TakNone');

$path = './file.unknown';

list($file,$key,$iv) = $client->upload_secret_file(path : $path);

$attributes = array($client->secret->documentAttributeFilename(file_name : $path));

$media = $client->secret->decryptedMessageMediaDocument(thumb : strval(null),thumb_w : 0,thumb_h : 0,mime_type : mime_content_type($path),size : filesize($path),key : $key,iv : $iv,attributes : $attributes,caption : 'The caption');

$client->send_secret_file(peer : $peer,file : $file,message : 'The caption',ttl : 10,media : $media);
```

---

## send_secret_media()

Sends a photo, video, audio or document in a secret chat with encryption and self‐destruct

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- peer(<small>string</small>,<small>int</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - The recipient ( user ID, username, or object )

- path(<small>string</small>) <kbd style="color : red">required</kbd> :
  - Local filesystem path to the media file

- caption(<small>string</small>) <kbd style="color : red">required</kbd> :
  - Caption text for the media

- ttl(<small>int</small>) <kbd style="color : red">required</kbd> :
  - Self‑destruct timer in seconds

##### <pre>Returns</pre>
An instance of [SentEncryptedMessage](https://tl.liveproto.dev/#/type/messages.SentEncryptedMessage)

##### <pre>Example</pre>
```php
$peer = $client->get_input_peer('@TakNone');

$client->send_secret_media(peer : $peer,path : './file.unknown',caption : 'The caption',ttl : 10);
```

---

## start_secret_chat()

Initiates a new secret chat session ( Diffie‑Hellman handshake )

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- peer(<small>string</small>,<small>int</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - User to start a secret chat with ( ID, username, or object )

##### <pre>Returns</pre>
An instance of [EncryptedChat](https://tl.liveproto.dev/#/type/EncryptedChat)

##### <pre>Example</pre>
```php
$peer = $client->get_input_peer('@TakNone');

$client->start_secret_chat(peer : $peer);
```

---

## close_secret_chat()

Closes an active secret chat, destroying keys

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- peer(<small>string</small>,<small>int</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - Peer whose secret chat should be closed

- ...arguments(<small>mixed</small>) <kbd onclick = "alert('default : empty')">optional</kbd> :
  - Any additional parameters you give will be passed to the [discardEncryption](https://tl.liveproto.dev/#/method/messages.discardEncryption)

##### <pre>Returns</pre>
Bool

##### <pre>Example</pre>
```php
$peer = $client->get_input_peer('@TakNone');

$client->close_secret_chat(peer : $peer);
```

---

## getTTL()

Gets the current default TTL for messages in the secret chat

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- peer(<small>string</small>,<small>int</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - Peer whose TTL you want to retrieve

##### <pre>Returns</pre>
Integer TTL value (seconds)

##### <pre>Example</pre>
```php
$peer = $client->get_input_peer('@TakNone');

$ttl = $client->getTTL(peer : $peer);
```

---

## get_secret_chat()

Retrieves the secret chat object for a given peer

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- peer(<small>string</small>,<small>int</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - Peer whose secret chat you want to get

##### <pre>Returns</pre>
Array representing the secret chat state

##### <pre>Example</pre>
```php
$peer = $client->get_input_peer('@TakNone');

$chat = $client->get_secret_chat(peer : $peer);

var_dump($chat);
```

---

## remove_secret_chat()

Removes secret chat data for a specific peer

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- peer(<small>string</small>,<small>int</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - Peer whose secrets should be removed

##### <pre>Returns</pre>
void

##### <pre>Example</pre>
```php
$peer = $client->get_input_peer('@TakNone');

$client->remove_secret(peer : $peer);
```

---

## perform_upload()

Performs an upload using the requested transfer kind. Delegates to file, stream or callback upload handlers. Return value depends on the chosen transfer kind

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- source(<small>mixed</small>) <kbd style="color : red">required</kbd> :
  - Source of the content to upload. Can be a local path or callback

- size(<small>int</small>) <kbd onclick = "alert('default : -1')">optional</kbd> :
  - Total size of the content in bytes. Use negative / zero for unknown / streaming sources

- dc_id(<small>int</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Data center id to use for the upload ( when null the client chooses one )

- progresscallback(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional callback invoked with upload progress ( percentage as float )

- key(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional encryption key ( used for secret / encrypted uploads or CDN encryption )

- iv(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional initialization vector ( IV ) for encryption

- transfer_kind(<small>TransferKind</small>) <kbd onclick = "alert('default : TransferKind::FILE')">optional</kbd> :
  - How to transfer data : file ( read ), stream ( generator ), or callback ( The output that is obtained from calling it )

##### <pre>Returns</pre>
An instance of [inputFile](https://tl.liveproto.dev/#/type/InputFile) or [InputEncryptedFile](https://tl.liveproto.dev/#/type/InputEncryptedFile), a [Generator](https://www.php.net/manual/en/class.generator) when streaming depending on `transfer_kind`

##### <pre>Example</pre>
```php
$client->perform_upload(source : '/temp/file.bin');
```

---

## upload_chunks()

Uploads data in chunks using multiple connections/parallelism and supports CDN/Big-file handling. Yields chunk requests and finally returns an InputFile/InputFileBig or InputEncryptedFile variant depending on encryption and size

Usable by :
- [x] Users
- [x] Bots

> [!NOTE]
> Array

##### <pre>Arguments</pre>
- size(<small>int</small>) <kbd onclick = "alert('default : -1')">optional</kbd> :
  - Total size in bytes. negative / zero indicates unknown / streaming. used to choose chunk sizes and big-file logic

- dc_id(<small>int</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Data center ID to perform uploads against. When null the client default DC is used

- progresscallback(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional callback invoked with progress percentage. Returning / awaiting false can cancel the upload

- key(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional AES key, when provided the chunks will be encrypted ( used for secret uploads or CDN encryption )

- iv(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional AES IV used together with `key` for encryption

- offset(<small>int</small>) <kbd onclick = "alert('default : 0')">optional</kbd> :
  - Byte offset where uploading should start ( useful for resume or ranged uploads )

- limit(<small>int</small>) <kbd onclick = "alert('default : 512')">optional</kbd> :
  - Maximum request chunk size ( KB ) used to compute the bytes requested / sent, internally aligned and multiplied by 1024

##### <pre>Returns</pre>
A [Generator](https://www.php.net/manual/en/class.generator) that yields chunk control pairs ( send chunk bytes to it ). On completion it returns an instance of [inputFile](https://tl.liveproto.dev/#/type/InputFile) or [InputEncryptedFile](https://tl.liveproto.dev/#/type/InputEncryptedFile)

##### <pre>Example</pre>
```php
$generator = $client->upload_chunks();

foreach($chunks as $chunk){
	$generator->send($chunk);
}
$result = $generator->getReturn();

var_dump($result);
```

---

## upload_callback()

Convenience wrapper that feeds upload_chunks with data produced by a user-provided callable. The callable receives ( offset , limit ) and must return the next chunk bytes to upload

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- lambda(<small>callable</small>) <kbd style="color : red">required</kbd> :
  - A callable invoked to provide each chunk. Signature : function(offset,limit) : string | null - return null to signal completion

- size(<small>int</small>) <kbd onclick = "alert('default : -1')">optional</kbd> :
  - Total size in bytes (or -1 for streaming)

- dc_id(<small>int</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional data-center ID for uploads

- progresscallback(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional progress callback receiving percentages

- key(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional encryption key for the upload stream

- iv(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional IV used for encryption

##### <pre>Returns</pre>
Returns whatever the underlying `upload_chunks` generator returns - typically an instance of [inputFile](https://tl.liveproto.dev/#/type/InputFile) or [InputEncryptedFile](https://tl.liveproto.dev/#/type/InputEncryptedFile)

##### <pre>Example</pre>
```php
$stream = fopen('./video.mp4','r');

$result = $client->upload_callback(function(int $offset,int $limit) use($stream) : string | null {
	if(fseek($stream,$offset,SEEK_SET) === 0){
		return fread($stream,$limit);
	} else {
		return null;
	}
});
```

---

## upload_file()

Uploads a file to Telegram servers

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- path(<small>string</small>) <kbd style="color : red">required</kbd> :
  - Path to the file on the local disk

- dc_id(<small>int</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Data center id to use for the upload ( when null the client chooses one )

- progresscallback(<small>callable</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional function to receive progress updates (percentage)

- key(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional AES encryption key if the file should be encrypted

- iv(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional initialization vector (IV) for encryption

##### <pre>Returns</pre>
An instance of [inputFile](https://tl.liveproto.dev/#/type/InputFile) or [InputEncryptedFile](https://tl.liveproto.dev/#/type/InputEncryptedFile)

##### <pre>Example</pre>
```php
$start = microtime(true);

$client->upload_file(path : './file.mp4',progresscallback : function(float $percent) use($start) : bool {
	$finish = microtime(true);

	echo 'Process progress percentage : ' , intval($percent) , '%' , PHP_EOL;

	/* If the output is false the process will stop , so I wrote a timeout example here for 2 minutes */
	return boolval($finish - $start < 2 * 60);
});
```

---

## upload_secret_file()

Encrypts and uploads a file for secret chats, returning metadata and encryption info

Usable by :
- [x] Users
- [ ] Bots

##### <pre>Arguments</pre>
- path(<small>string</small>) <kbd style="color : red">required</kbd> :
  - Path to the file on local disk to be encrypted and uploaded

- ...arguments(<small>mixed</small>) <kbd onclick = "alert('default : empty')">optional</kbd> :
  - Any additional parameters you give will be passed to the [upload_file](en/functions.md#upload_file)

##### <pre>Returns</pre>
An array containing An instance of [InputEncryptedFile](https://tl.liveproto.dev/#/type/InputEncryptedFile) and encryption details

##### <pre>Example</pre>
```php
$peer = $client->get_input_peer('@TakNone');

$path = './file.unknown';

list($file,$key,$iv) = $client->upload_secret_file(path : $path);

$attributes = array($client->secret->documentAttributeFilename(file_name : $path));

$media = $client->secret->decryptedMessageMediaDocument(thumb : strval(null),thumb_w : 0,thumb_h : 0,mime_type : mime_content_type($path),size : filesize($path),key : $key,iv : $iv,attributes : $attributes,caption : 'The caption');

$client->send_secret_file(peer : $peer,file : $file,message : 'The caption',ttl : 10,media : $media);
```

---

## send_content()

Sends text or media to a peer, Long text is split into chunks and sent as multiple messages. Supports parse modes ( HTML / Markdown ), uploaded media or existing media objects, and returns either a single Message or an array of Messages when multiple were sent

Usable by :
- [x] Users
- [x] Bots

> [!NOTE]
> The message text is divided into 4096-byte chunks. If it is placed in the media caption, this value is reduced to 1024 characters, and as a result, the function output will be an array of objects

##### <pre>Arguments</pre>
- peer(<small>string</small>,<small>int</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - Target peer ( username , ID , or peer object )

- message(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - It can be the text of a message or the caption of a media

- parse_mode(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional parse mode for formatting ( e.g. , "HTML" , "Markdown" , "MarkdownV2" ) The method will convert to entities when supported

- media(<small>string</small>,<small>object</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Either a path / string referencing an uploaded file, an existing media object, or null for no media

- send_as(<small>string</small>,<small>int</small>,<small>null</small>,<small>object</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Send this message as the specified peer

- file_type(<small>FileType</small>) <kbd onclick = "alert('default : FileType::DOCUMENT')">optional</kbd> :
  - Specifies how to treat a string media ( document , photo , etc.) Uses the [`FileType`](en/enums.md#FileType) enum

- uploaded(<small>array</small>) <kbd onclick = "alert('default : array()')">optional</kbd> :
  - Optional array of upload-related metadata / options passed to get_input_media_uploaded ( e.g. , attributes )

- args(<small>mixed</small>) <kbd onclick = "alert('default : empty')">optional</kbd> :
  - Additional variadic arguments forwarded to lower-level [`sendMessage`](https://tl.liveproto.dev/#/method/messages.sendMessage) / [`sendMedia`](https://tl.liveproto.dev/#/method/messages.sendMedia) calls ( e.g. , reply_markup , schedule_date )

##### <pre>Returns</pre>
An instance of [Message](https://core.telegram.org/constructor/message) or an array of [Message](https://core.telegram.org/constructor/message) instances when multiple messages were sent

##### <pre>Example</pre>
```php
$client->send_content(peer : '@TakNone',message : 'Hello <b>world</b> !',parse_mode : 'HTML'); // send text only

$attributes = array($client->documentAttributeVideo(duration : 60,w : 512,h : 512)); // this is optional

$client->send_content(peer : '@LiveProtoChat',message : 'This is the media caption',media : './video.mp4',uploaded : ['attributes'=>$attributes]); // send an uploaded media ( path ) as a document with extra upload options
```

---

## fetch_messages()

Fetches messages with many optional filters ( IDs , text query , replies , date , ID ranges , etc ) Returns an iterator that yields message objects matching the filters

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- peer(<small>string</small>,<small>int</small>,<small>null</small>,<small>object</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Target peer ( username , ID , or peer object ) If omitted , the current dialog / context is used

- offset_peer(<small>string</small>,<small>int</small>,<small>null</small>,<small>object</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Peer used as offset for pagination

- offset(<small>int</small>) <kbd onclick = "alert('default : 0')">optional</kbd> :
  - Numeric offset ( page index ) used for pagination

- offset_id(<small>int</small>) <kbd onclick = "alert('default : 0')">optional</kbd> :
  - Message ID offset used for fetching relative to a specific message

- offset_date(<small>int</small>) <kbd onclick = "alert('default : 0')">optional</kbd> :
  - Date ( unix timestamp ) offset used for pagination

- limit(<small>int</small>) <kbd onclick = "alert('default : 100')">optional</kbd> :
  - Maximum number of messages to retrieve in one request / batch

- min_id(<small>int</small>) <kbd onclick = "alert('default : 0')">optional</kbd> :
  - Minimum message ID to include ( filters out older messages )

- max_id(<small>int</small>) <kbd onclick = "alert('default : 0')">optional</kbd> :
  - Maximum message ID to include ( filters out newer messages )

- min_date(<small>int</small>) <kbd onclick = "alert('default : 0')">optional</kbd> :
  - Minimum unix date ( timestamp ) to include

- max_date(<small>int</small>) <kbd onclick = "alert('default : 0')">optional</kbd> :
  - Maximum unix date ( timestamp ) to include

- unread_mentions(<small>bool</small>) <kbd onclick = "alert('default : ')">optional</kbd> :
  - If true , only messages with unread mentions will be included

- unread_reactions(<small>bool</small>) <kbd onclick = "alert('default : ')">optional</kbd> :
  - If true,only messages with unread reactions will be included

- recent_locations(<small>bool</small>) <kbd onclick = "alert('default : ')">optional</kbd> :
  - If true , include recent location messages ( used for live location listing )

- posts(<small>bool</small>) <kbd onclick = "alert('default : ')">optional</kbd> :
  - If true , fetch channel / saved post messages ( post-type entries )

- search(<small>bool</small>) <kbd onclick = "alert('default : ')">optional</kbd> :
  - If true , the call performs a search ( affects how query / filter are interpreted )

- saved(<small>bool</small>) <kbd onclick = "alert('default : ')">optional</kbd> :
  - If true , include saved messages ( your Saved Messages )

- scheduled(<small>bool</small>) <kbd onclick = "alert('default : ')">optional</kbd> :
  - If true , include scheduled messages ( for channels or scheduled dialogs )

- id(<small>array&lt;int&gt;</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional list of message IDs to fetch exactly, When provided the call returns those messages ( Vector of ints )

- filter(<small>object&lt;MessagesFilter&gt;</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional filter object ( [`MessagesFilter`](https://tl.liveproto.dev/#/type/MessagesFilter) ) to restrict message types ( photos , video , gifs , etc )

- query(<small>string</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Text query used when performing a text search ( only used if `search` / `posts` is true or special occasions )

- reply_to(<small>int</small>,<small>bool</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - If set to a message id , fetches replies to that message, if true / false used by some code-paths to indicate reply-only behavior

- shortcut_id(<small>int</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional shortcut identifier used by client-side UI to fetch messages for a quick action

- hashgen(<small>Closure</small>,<small>array</small>,<small>null</small>) <kbd onclick = "alert('default : null')">optional</kbd> :
  - Optional hash generator ( Closure or array of indexes ) used for caching / validation of message sets

- ...args(<small>mixed</small>) <kbd onclick = "alert('default : empty')">optional</kbd> :
  - Any additional variadic parameters passed through to lower-level calls

##### <pre>Returns</pre>
An iterator yielding instances of [Message](https://tl.liveproto.dev/#/type/Message)

##### <pre>Example</pre>
```php
$messages = $client->fetch_messages(peer : '@TakNone',unread_mentions : true);

$messages = $client->fetch_messages(posts : true,hashtag : 'liveproto');

$messages = $client->fetch_messages(peer : '@LiveProto',id : [13,22]);

$messages = $client->fetch_messages(peer : 'me',scheduled : true);

$messages = $client->fetch_messages(search : true,query : 'LiveProto');

$messages = $client->fetch_messages(peer : '@LiveProtoChat',reply_to : 23);

/*
 * Now let's look at the results of each of the above models
 * All of these results are of the Message type
 */
foreach($messages as $message){
	try {
		echo json_encode($message,flags : JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT) , PHP_EOL;
	} catch(Throwable){
		var_dump($message);
	}
}
```

---

## get_input_channel()

Resolves and returns an input-channel representation for a channel ( InputChannel ) which is used in API calls that require an InputPeer-like channel reference

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- channel(<small>string</small>,<small>int</small>,<small>null</small>,<small>object</small>) <kbd style="color : red">required</kbd> :
  - Channel identifier ( username , ID , or channel entity )

##### <pre>Returns</pre>
An instance of [InputChannel](https://tl.liveproto.dev/#/type/InputChannel)

##### <pre>Example</pre>
```php
$inputChannel = $client->get_input_channel('@Telegram');
```

---

## get_input_channel_from_message()

Defines a min channel that was seen in a certain message of a certain chat

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- message(<small>object</small>) <kbd style="color : red">required</kbd> :
  - The message object an instance of [Message](https://core.telegram.org/constructor/message)

##### <pre>Returns</pre>
An instance of [InputChannel](https://tl.liveproto.dev/#/type/InputChannel)

##### <pre>Example</pre>
```php
$inputChannel = $client->get_input_channel_from_message(message : $message);
```

---

## get_input_user()

Resolves and returns an input user object to be used in API methods

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- user(<small>string</small>,<small>int</small>,<small>object</small>,<small>null</small>) <kbd style="color : red">required</kbd> :
  - User identifier (username, ID, or full user object)

##### <pre>Returns</pre>
An instance of [InputUser](https://tl.liveproto.dev/#/type/InputUser)

##### <pre>Example</pre>
```php
$inputPeer = $client->get_input_peer('me');

$inputUser = $client->get_input_user('@LiveProtoBot');

$client->messages->startBot(bot : $inputUser,peer : $inputPeer,start_param : 'love',random_id : random_int(PHP_INT_MIN,PHP_INT_MAX));
```

---

## get_input_user_from_message()

Defines a min user that was seen in a certain message of a certain chat

Usable by :
- [x] Users
- [x] Bots

##### <pre>Arguments</pre>
- message(<small>object</small>) <kbd style="color : red">required</kbd> :
  - The message object an instance of [Message](https://core.telegram.org/constructor/message)

##### <pre>Returns</pre>
An instance of [InputUser](https://tl.liveproto.dev/#/type/InputUser)

##### <pre>Example</pre>
```php
$inputUser = $client->get_input_user_from_message(message : $message);
```

---

## get_me()

Returns the current authorized user or bot

Usable by :
- [x] Users
- [x] Bots

##### <pre>Returns</pre>
An instance of [User](https://tl.liveproto.dev/#/constructor/user)

##### <pre>Example</pre>
```php
var_dump($client->get_me());
```

---

## is_bot()

Checks whether the current client is authorized as a bot

Usable by :
- [x] Users
- [x] Bots

##### <pre>Returns</pre>
Returns true if the client is a bot, false if a user

##### <pre>Example</pre>
```php
var_dump($client->is_bot());
```

---

?> Note, The ones you read were only custom methods created by the library, not methods you can call directly ! [Call Raw Functions](en/invoking.md)
