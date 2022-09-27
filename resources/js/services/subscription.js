import api from "./api";
import TokenService from "./token";

const API_URL = 'http://advance-stream-stats.test/api';

class Subscription {
    getAuthorization() {
      if(TokenService.isBrainTreeTokenExists() && TokenService.isBrainTreeTokenNotExpired()) {
        return TokenService.getBrainTreeClientToken();
      }

      return api.get(`${API_URL}/auth/token/generate`)
        .then((response) => {
          if (response.data.status) {
              TokenService.setBrainTreeClientToken(response.data.token);
          }

          return TokenService.getBrainTreeClientToken();
        });
    }

    getPlans() {
      let plans = [];
      return api.get(`${API_URL}/plans`)
        .then((response) => {
          if (response.data.status) {
              plans = response.data.plans;
              return plans;
          }

          return plans;
        });
    }

    subscribe(data) {
      return api.post(`${API_URL}/subscriptions`, JSON.stringify(data))
        .then((response) => {
          if (response.data.status) {
            TokenService.setUser(response.data.user);
          }

          return response.data;
        });
    }

    cancel(subscription) {
      return api.delete(`${API_URL}/subscriptions/${subscription}`)
        .then((response) => {
          if (response.data.status) {
            TokenService.setUser(response.data.user);
          }

          return response.data;
        });
    }
}

export default new Subscription();