import {
    QUESTION_LIST_RECEIVED,
    QUESTION_LIST_REQUEST,
    QUESTION_LIST_ERROR,
    QUESTION_LIST_UNLOAD
} from "../actions/constants";

export default(state = {
    questionList: null,
    isFetching: false
}, action) => {
    switch (action.type) {
            case QUESTION_LIST_REQUEST:
                return {
                    ...state,
                    isFetching: true,
                };
            case QUESTION_LIST_RECEIVED:
                return {
                    ...state,
                    questionList: action.data['hydra:member'],
                    isFetching: false
                };
            case QUESTION_LIST_ERROR:
            case QUESTION_LIST_UNLOAD:
            return {
                ...state,
                isFetching: false,
                questionList: null
            };
            default:
            return state;
    }
}