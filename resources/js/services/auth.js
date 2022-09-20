import api from "./api";
import TokenService from "./token";

const API_URL = 'http://advance-stream-stats.test/api';

class Auth {
   async login({ email, password }) {
    await api
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

  async logout() {
    await api
      .post(`${API_URL}/auth/logout`, {})
      .then((response) => {
        if (response.data.status) {
          TokenService.removeUser();
        }

        return response.data;
      });
    
  }
}

export default new Auth();