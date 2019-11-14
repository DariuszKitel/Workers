import {requests} from "../agent";

export const WORK_POST_REQUEST = 'WORK_POST_REQUEST';
export const WORK_POST_RECEIVED = 'WORK_POST_RECEIVED';
export const WORK_POST_ERROR = 'WORK_POST_ERROR';
export const WORK_POST_ADD = 'WORK_POST_ADD';

export const workPostRequest = () => ({
    type: WORK_POST_REQUEST,
});

export const workPostError = (error) => ({
    type: WORK_POST_ERROR,
    error
});

export const workPostReceived = (data) => ({
    type: WORK_POST_RECEIVED,
    data
});

export const workPostFetch = () => {
    return (dispatch) => {
        dispatch(workPostRequest());
        return requests.get('/work_posts')
            .then(response => dispatch(workPostReceived(response)))
            .catch(error => dispatch(workPostError(error)));
    }
};

export const workPostAdd = () => ({
    type: WORK_POST_ADD,
    data: {
        id: Math.floor(Math.random() * 100 +3),
        title: 'A new post added!'
    }
});