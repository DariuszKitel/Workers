import {WORK_POST_RECEIVED, WORK_POST_ADD, WORK_POST_REQUEST, WORK_POST_ERROR} from "../actions/actions";

export default(state = {
    posts: null,
    isFetching: false
}, action) => {
    switch (action.type) {
        case WORK_POST_REQUEST:
        return {
            ...state,
            isFetching: true,
        };
        case WORK_POST_RECEIVED:
            return {
                ...state,
                posts: action.data['hydra:member'],
                isFetching: false
            };
        case WORK_POST_ERROR:
            return {
                ...state,
                isFetching: false,
                posts: null
            };
        case WORK_POST_ADD:
        return {
            ...state,
            posts: state.posts ? state.posts.concat(action.data) : state.posts
        };
        default:
            return state;
    }
}