import api from './api';

class User {
  getUser() {
    return api.get('/test/user');
  }
}

export default new User();