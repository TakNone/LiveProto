# Bound Methods

?> Note, Here, we’re going to discuss the methods related to [update handlers](en/handlers.md), If you use the library’s custom handler attributes, they provide built-in methods that make things easier for you. However, using them is completely optional

---

### getPeer

> Used to get input peer

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `peer` | `mixed` | `null` | If the value remains `null`, it will return the peer to whom the update belongs.If you pass any other value, it will be passed to the [`get_input_peer`](en/methods.md#get_input_peer) |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

- [`CallbackQuery`](en/handlers.md#CallbackQuery)

- [`ChosenInlineResult`](en/handlers.md#ChosenInlineResult)

- [`InlineQuery`](en/handlers.md#InlineQuery)

- [`NewStory`](en/handlers.md#NewStory)

- [`NewJoinRequest`](en/handlers.md#NewJoinRequest)

</details>

---

### getPeerId

> Used to get peer ID

> [!NOTE]
> There are no parameters for this method

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

- [`CallbackQuery`](en/handlers.md#CallbackQuery)

- [`ChosenInlineResult`](en/handlers.md#ChosenInlineResult)

- [`InlineQuery`](en/handlers.md#InlineQuery)

- [`NewStory`](en/handlers.md#NewStory)

- [`NewJoinRequest`](en/handlers.md#NewJoinRequest)

</details>

---

### respond

> Used to send messages to the same peer

> [!TIP]
> The `peer` parameter is already filled in for the `send_content` method

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [send_content](https://docs.liveproto.dev/#/en/methods?id=send_content) |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

- [`CallbackQuery`](en/handlers.md#CallbackQuery)

- [`ChosenInlineResult`](en/handlers.md#ChosenInlineResult)

- [`InlineQuery`](en/handlers.md#InlineQuery)

- [`NewStory`](en/handlers.md#NewStory)

- [`NewJoinRequest`](en/handlers.md#NewJoinRequest)

</details>

---

### reply

> Used to reply to a message from the same peer

> [!TIP]
> The `peer` parameter is already filled in for the `send_content` method

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [send_content](https://docs.liveproto.dev/#/en/methods?id=send_content) |
| `input_reply_to` | `array` | empty `array()` | The values of this variable are passed to [InputReplyTo](https://tl.liveproto.dev/#/type/InputReplyTo) **except for** the values of `reply_to_msg_id` and `peer` and `story_id`  |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

- [`CallbackQuery`](en/handlers.md#CallbackQuery)

- [`NewStory`](en/handlers.md#NewStory)

</details>

---

### forward

> Used to forward message from current peer

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `peer` | `mixed` | <kbd style="color : red">required</kbd> | This is to specify which peer to forward the message to |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [forwardMessages](https://tl.liveproto.dev/#/method/messages.forwardMessages) For stories, they are passed to the the [sendMedia](https://tl.liveproto.dev/#/method/messages.sendMedia) |
| `input_reply_to` | `array` | empty `array()` | The values of this variable are passed to [InputReplyTo](https://tl.liveproto.dev/#/type/InputReplyTo) **including** the values of `reply_to_msg_id` or `peer` and `story_id`  |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

- [`CallbackQuery`](en/handlers.md#CallbackQuery)

- [`NewStory`](en/handlers.md#NewStory)

</details>

---

### edit

> Used to edit a message on the current peer

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `message` | `string` | `null` | The new text of the message that is to be changed |
| `media` | `object` | `null` | The new media of the message that is to be changed |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [editMessage](https://tl.liveproto.dev/#/method/messages.editMessage) or [editInlineBotMessage](https://tl.liveproto.dev/#/method/messages.editInlineBotMessage) for inline bot message |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

- [`CallbackQuery`](en/handlers.md#CallbackQuery)

- [`ChosenInlineResult`](en/handlers.md#ChosenInlineResult)

</details>

---

### pin

> Used to pin the current message

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [updatePinnedMessage](https://tl.liveproto.dev/#/method/messages.updatePinnedMessage) |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

- [`CallbackQuery`](en/handlers.md#CallbackQuery)

</details>

---

### unpin

> Used to unpin the current message

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `all` | `bool` | `false` | If true, all messages in the current chat will be unpinned |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [updatePinnedMessage](https://tl.liveproto.dev/#/method/messages.updatePinnedMessage), If the `all` parameter is `true`, they are passed to the [unpinAllMessages](https://tl.liveproto.dev/#/method/messages.unpinAllMessages) |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

- [`CallbackQuery`](en/handlers.md#CallbackQuery)

</details>

---

### delete

> Used to delete the current message

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `revoke` | `true` | `null` | If true, messages for all participants of the chat will be deleted |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

- [`CallbackQuery`](en/handlers.md#CallbackQuery)

</details>

---

### reaction

> Used to react to the current message

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `reaction` | `mixed` | <kbd style="color : red">required</kbd> | If it is null, the reaction will be deleted, if it is a string, the same emoji will be selected as the reaction, if it is a integer, its custom emoji will be selected, and if it is an array, a list of reactions will be played simultaneously |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [sendReaction](https://tl.liveproto.dev/#/method/messages.sendReaction) or [sendReaction](https://tl.liveproto.dev/#/method/stories.sendReaction) for stories |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

- [`CallbackQuery`](en/handlers.md#CallbackQuery)

- [`NewStory`](en/handlers.md#NewStory)

</details>

---

### paidReaction

> Used to sends one or more paid Telegram Star reactions

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `count` | `int` | `1` | The number of reactions you want to apply to the message |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [sendPaidReaction](https://tl.liveproto.dev/#/method/messages.sendPaidReaction) |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

- [`CallbackQuery`](en/handlers.md#CallbackQuery)

</details>

---

### answerCallback

> Used to set the callback answer to a user button press ( bots only )

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `cache` | `int` | <kbd style="color : red">required</kbd> | Cache validity time |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [setBotCallbackAnswer](https://tl.liveproto.dev/#/method/messages.setBotCallbackAnswer) |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- [`CallbackQuery`](en/handlers.md#CallbackQuery)

</details>

---

### answerInline

> Used to answer an inline query ( bots only )

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `results` | `array` | <kbd style="color : red">required</kbd> | Vector of [results](https://tl.liveproto.dev/#/type/InputBotInlineResult) for the inline query |
| `cache` | `int` | <kbd style="color : red">required</kbd> | The maximum amount of time in seconds that the result of the inline query may be cached on the server |
| `switch_text` | `string` | `null` | Text for the button that switches the user to a private chat with the bot and sends the bot a start message with the parameter `start_parameter` or Text of the button `webapp` |
| `switch_url` | `string` | `null` | The `webapp` URL |
| `start_param` | `string` | `null` | The parameter for the `/start parameter` |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [setInlineBotResults](https://tl.liveproto.dev/#/method/messages.setInlineBotResults) |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- [`InlineQuery`](en/handlers.md#InlineQuery)

</details>

---

### getLink

> Used to get the current peer story link

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [exportStoryLink](https://tl.liveproto.dev/#/method/stories.exportStoryLink) |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- [`NewStory`](en/handlers.md#NewStory)

</details>

---

### getStories

> Used to get all current peer stories

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [getPeerStories](https://tl.liveproto.dev/#/method/stories.getPeerStories) |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- [`NewStory`](en/handlers.md#NewStory)

</details>

---

### hideRequest

> Used to hide user joining requests

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `approved` | `true` | `null` | If true, The last user's request is accepted provided that `all` is `false` |
| `all` | `bool` | `false` | If true, All requests are hidden |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [hideAllChatJoinRequests](https://tl.liveproto.dev/#/method/messages.hideAllChatJoinRequests) , provided that `all` equals `true` |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- [`NewJoinRequest`](en/handlers.md#NewJoinRequest)

</details>

---

### getReply

> Used to receive a message / story that has been replied to, If the message has not been replicated to anything, `false` is returned

> [!NOTE]
> There are no parameters for this method

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

</details>

---

### screenshot

> Notify the other user in a private chat that a screenshot of the chat was taken

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `reply_to` | `array` | empty `array()` | The values of this variable are passed to [InputReplyTo](https://tl.liveproto.dev/#/type/InputReplyTo) except for the values of `reply_to_msg_id` |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

</details>

---

### block

> Used to block a user

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [block](https://tl.liveproto.dev/#/method/contacts.block) |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

</details>

---

### unblock

> Used to unblock a user

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [unblock](https://tl.liveproto.dev/#/method/contacts.unblock) |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

</details>

---

### download

> Used to download media

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `path` | `string` | <kbd style="color : red">required</kbd> | The path where the media is to be downloaded and saved |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [download_media](en/methods.md#download_media) |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

- [`NewStory`](en/handlers.md#NewStory)

</details>

---

### click

> Used to click the message button

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [click_button](en/methods.md#click_button) |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

</details>

---

### format

> Formats a plain string using a given set of message entities

> [!NOTE]
> There are no parameters for this method

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

</details>

---

### resolveSuggestion

> approve / reject suggested posts

| Parameter | Type | Default | Description |
| :---: | :---: | :---: | :--- |
| `...args` | `mixed` | <kbd style="color : dodgerblue">optional</kbd> | Any additional parameters you give will be passed to the [toggleSuggestedPostApproval](https://tl.liveproto.dev/#/method/messages.toggleSuggestedPostApproval) |

<details>
<summary style="color : slateblue">Belongs to which handlers ?!</summary>

- Messages 
  - [`NewMessage`](en/handlers.md#NewMessage)
  - [`MessageEdited`](en/handlers.md#MessageEdited)
  - [`NewScheduledMessage`](en/handlers.md#NewScheduledMessage)

</details>