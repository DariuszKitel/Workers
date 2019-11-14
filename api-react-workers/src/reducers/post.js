import {POST_ERROR, POST_RECEIVED, POST_REQUEST, POST_UNLOAD} from "../actions/constants";


export default (state = {
    post: null,
    isFetching: false
}, action) => {
    switch (action.type) {
        case POST_REQUEST:
            return {
                ...state,
                isFetching: true
            };
        case POST_RECEIVED:
            return {
                ...state,
                post: action.data,
                isFetching: false
            };
        case POST_ERROR:
            return {
                ...state,
                isFetching: false
            };
        case POST_UNLOAD:
            return {
                ...state,
                isFetching: false,
                post: null
            };
        default:
            return state;
    }
}