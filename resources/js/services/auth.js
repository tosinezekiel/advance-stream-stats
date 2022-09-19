import api from "./api";
import TokenService from "./token";

const API_URL = 'http://advance-stream-stats.test/api';

class Auth {
   login({ email, password }) {
    return api
      .post(`${API_URL}/auth/login`, {
        email,
        password
      })
      .then((response) => {
        if (response.data.authorization.token) {
            TokenService.setUser(response.data.user);
            TokenService.updateLocalAccessToken(response.data.authorization.token);
        }

        return response.data;
      });
  }

  logout() {
    TokenService.removeUser();
  }
}

export default new Auth();