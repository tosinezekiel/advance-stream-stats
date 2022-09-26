import api from './api';

class User {
  getUser() {
    return api.get('/test/user').then((response) => {
      if (response.data.status) {
          TokenService.setUser(response.data.user);
      }

      return response.data;
    });
  }
}

export default new User();