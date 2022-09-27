import AuthService from '../services/auth';
import UserService from '../services/user';
import SubscriptionService from '../services/subscription';

const user = JSON.parse(localStorage.getItem('user'));
const initialState = user
  ? { status: { loggedIn: true }, user }
  : { status: { loggedIn: false }, user: null };

export const auth = {
  namespaced: true,
  state: initialState,
  getters: {
    isLoggedIn(state){
      return state.status.loggedIn
    },
    getUser(state){
      return state.user
    },
    isSubscribed(state){
      return state.user?.subscribed
    },
    getSubscriptions(state){
      return state.user?.subscriptions
    }
  },
  actions: {
    login({ commit }, user) {
      return AuthService.login(user).then(
        user => {
          commit('loginSuccess', user);
          return Promise.resolve(user);
        },
        error => {
          commit('loginFailure');
          return Promise.reject(error);
        }
      );
    },
    logout({ commit }) {
      AuthService.logout();
      commit('logout');
    },
    user({ commit }, user) {
      return UserService.getUser(user).then(
        user => {
          commit('loginSuccess', user);
          return Promise.resolve(user);
        },
        error => {
          return Promise.reject(error);
        }
      );
    },
    cancel({ commit }, subscription) {
      return SubscriptionService.cancel(subscription).then(
        user => {
          commit('loginSuccess', user);
          return Promise.resolve(user);
        },
        error => {
          return Promise.reject(error);
        }
      );
    },
    refreshToken({ commit }, accessToken) {
      commit('refreshToken', accessToken);
    }
  },
  mutations: {
    loginSuccess(state, user) {
      state.status.loggedIn = true;
      state.user = user;
    },
    loginFailure(state) {
      state.status.loggedIn = false;
      state.user = null;
    },
    logout(state) {
      state.status.loggedIn = false;
      state.user = null;
    },
    refreshToken(state, accessToken) {
      state.status.loggedIn = true;
      state.user = { ...state.user, accessToken: accessToken };
    }
  }
};