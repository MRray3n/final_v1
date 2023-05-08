class Chatbox {
  constructor() {
    this.args = {
      openButton: document.querySelector(".chatbox__button"),
      chatBox: document.querySelector(".chatbox__support"),
      sendButton: document.querySelector(".send__button"),
    };
    this.state = false;
    this.messages = [];
  }
  display() {
    const { openButton, chatBox, sendButton } = this.args;
    openButton.addEventListener("click", () => this.toggleState(chatBox));
    sendButton.addEventListener("click", () => this.onSendButton(chatBox));
    const node = chatBox.querySelector("input");
    node.addEventListener("keyup", ({ key }) => {
      if (key === "Enter") {
        this.onSendButton(chatBox);
      }
    });
  }
  //   checkCookie() {}

  toggleState(chatbox) {
    this.state = !this.state;
    // show or hides the box
    if (this.state) {
      chatbox.classList.add("chatbox--active");
    } else {
      chatbox.classList.remove("chatbox--active");
    }
  }
  
  onSendButton(chatbox) {
    var textField = chatbox.querySelector("input");
    let text1 = textField.value;
    if (text1 === "") {
      return;
    }
    // const encoder = new TextEncoder(Uint8Array);
    let msg1 = { name: "User", message: text1 };
    this.messages.push(msg1);

    var cook;
    var user="";
    let cname = "UserID";
    let name = cname + "=";
    let ca = document.cookie.split(";");
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == " ") {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0)   {
        user = c.substring(name.length, c.length);
        
      }
    }
    if (user != "")  {
      cook = { name: "UserID", UserID: user };
    } else{
      const date = new Date();
      const ip = window.location.hostname;
      user = ip + date.getTime();
      if (user != "" && user != null) {
        const d = new Date();
        d.setTime(d.getTime() + 365 * 24 * 60 * 60 * 1000);
        let expires = "expires=" + d.toUTCString();
        document.cookie = "UserID" + "=" + user + ";" + expires + ";path=/";
        cook = { name: "UserID", UserID: user };
      }
    }
    fetch($SCRIPT_ROOT + "/", {
      method: "POST",
      mode: "cors",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        message: encodeURIComponent(text1),
        UserID: user,
      }),
    })
      .then((r) => r.json())
      .then((r) => {
        let msg2 = { name: "Rayos", message: decodeURIComponent(r.answer) };
        console.log(decodeURIComponent(msg2.message));
        this.messages.push(msg2);
        this.updateChatText(chatbox);
        textField.value = "";
      })
      .catch((error) => {
        console.error("Error:", error);
        this.updateChatText(chatbox);
        textField.value = "";
      });
  }
  updateChatText(chatbox) {
    var html = "";
    this.messages
      .slice()
      .reverse()
      .forEach(function (item) {
        if (item.name === "Rayos") {
          html +=
            '<div class="messages__item messages__item--visitor">' +
            item.message +
            "</div>";
        } else {
          html +=
            '<div class="messages__item messages__item--operator">' +
            item.message +
            "</div>";
        }
      });

    const chatmessage = chatbox.querySelector(".chatbox__messages");

    chatmessage.innerHTML = html;
  }
}

const chatBox = new Chatbox();
chatBox.display();
