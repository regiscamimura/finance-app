import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex);


export default new Vuex.Store({
	state: {
        page: 1,
        entries: [],
        total: '',
        entry_add_show: false,
        loading: false,
        refresh: false,
	},
	mutations: {
		setState(state, [name, data]) {
			name = name.split('.');
			if (name[1] !== undefined ) {
				state[name[0]][name[1]] = data;
			}
			else state[name] = data;
		}
    }
})
