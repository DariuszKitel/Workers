import {
    WORK_POST_RECEIVED,
    WORK_POST_ADD,
    WORK_POST_REQUEST,
    WORK_POST_ERROR,
    WORK_POST_SET_PAGE
} from "../actions/constants";

export default(state = {
    posts: null,
    isFetching: false,
    currentPage: 1,
    pageCount: null
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
        state = {
            ...state,
            posts: state.posts ? state.posts.concat(action.data) : state.posts
        };
        return state;
        case WORK_POST_SET_PAGE:
            return {
                ...state,
                currentPage: action.page
            };
        default:
            return state;
    }
}