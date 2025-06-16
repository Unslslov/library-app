import { createStore } from 'vuex';
import admin from './admin';
import librarian from './librarian';
import client from './client';

export default createStore({
  modules: {
    admin,
    librarian,
    client
  },
});