import {requests} from "../agent";
import {
    POST_ERROR, POST_RECEIVED,
    POST_REQUEST, POST_UNLOAD,
    WORK_POST_ADD,
    WORK_POST_ERROR,
    WORK_POST_RECEIVED,
    WORK_POST_REQUEST
} from "./constants";


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

export const postRequest = () => ({
    type: POST_REQUEST
});

export const postError = (error) => ({
    type: POST_ERROR,
    error
});

export const postReceived = (data) => ({
    type: POST_RECEIVED,
    data
});

export const postUnload = () => ({
    type: POST_UNLOAD
});

export const postFetch = (id) => {
    return (dispatch) => {
        dispatch(postRequest());
        return requests.get(`/work_posts/${id}`)
            .then(response => dispatch(postReceived(response)))
            .catch(error => dispatch(postError(error)));
    }
};

export const workPostAdd = () => ({
    type: WORK_POST_ADD,
    data: {
        id: Math.floor(Math.random() * 100 +3),
        title: 'A new post added!'
    }
});