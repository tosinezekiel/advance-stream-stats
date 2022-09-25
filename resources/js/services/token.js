class Token {
  getLocalAccessToken() {
    const user = JSON.parse(localStorage.getItem("user"));
    return user?.accessToken;
  }

  updateLocalAccessToken(token) {
    let user = JSON.parse(localStorage.getItem("user"));
    user.accessToken = token;
    localStorage.setItem("user", JSON.stringify(user));
  }

  getUser() {
    return JSON.parse(localStorage.getItem("user"));
  }

  setUser(user) {
    localStorage.setItem("user", JSON.stringify(user));
  }

  removeUser() {
    localStorage.removeItem("user");
  }

  setBrainTreeClientToken(token) {
    const TWENTY_FOR_HOURS_IN_MILLISECONDS = 86400000;
    const now = new Date()
    const item = {
        value: token,
        expiry: now.getTime() + TWENTY_FOR_HOURS_IN_MILLISECONDS,
    }
    localStorage.setItem("bt_token", JSON.stringify(item))
  }

  isBrainTreeTokenExists() {
    const itemStr = localStorage.getItem('bt_token')
    if (!itemStr) {
        return false
    }

    return true;
  }

  isBrainTreeTokenNotExpired() {
    const itemStr = localStorage.getItem('bt_token')

    const item = JSON.parse(itemStr)
    const now = new Date()

    if (now.getTime() > item.expiry) {
      return false
    }

    return true
  }

  getBrainTreeClientToken() {
    const itemStr = localStorage.getItem('bt_token')
    const item = JSON.parse(itemStr)
    return item.value
  }
}

export default new Token();