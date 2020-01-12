import {requests} from "../agent";
import {
    POST_ERROR,
    POST_RECEIVED,
    POST_REQUEST,
    POST_UNLOAD, QUESTION_ADDED,
    QUESTION_LIST_ERROR,
    QUESTION_LIST_RECEIVED,
    QUESTION_LIST_REQUEST,
    QUESTION_LIST_UNLOAD,
    USER_LOGIN_SUCCESS, USER_PROFILE_ERROR, USER_PROFILE_RECEIVED, USER_PROFILE_REQUEST, USER_SET_ID,
    WORK_POST_ADD,
    WORK_POST_ERROR,
    WORK_POST_RECEIVED,
    WORK_POST_REQUEST
} from "./constants";
import {SubmissionError} from "redux-form";


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


export const questionListRequest = () => ({
    type: QUESTION_LIST_REQUEST
});

export const questionListError = (error) => ({
    type: QUESTION_LIST_ERROR,
    error
});

export const questionListReceived = (data) => ({
    type: QUESTION_LIST_RECEIVED,
    data
});

export const questionListUnload = () => ({
    type: QUESTION_LIST_UNLOAD
});

export const questionListFetch = (id) => {
    return (dispatch) => {
        dispatch(questionListRequest());
        return requests.get(`/work_posts/${id}/questions`)
            .then(response => dispatch(questionListReceived(response)))
            .catch(error => dispatch(questionListError(error)));
    }
};

export const questionAdded = (question) => ({
    type: QUESTION_ADDED,
    question
});

export const questionAdd = (question, workPostId) => {
    return (dispatch) => {
        return requests.post(
            '/questions',
            {
                content: question,
                workPost: `/api/work_post/${workPostId}`
            }
        ).then(response => dispatch(questionAdded(response)))
    }
};

export const userLoginSuccess = (token, userId) => {
    return {
        type: USER_LOGIN_SUCCESS,
        token,
        userId
    }
};

export const userLoginAttempt = (username, password) => {
    return (dispatch) => {
        return requests.post('/login_check', {username, password}, false).then(
            response => dispatch(userLoginSuccess(response.token, response.id))
        ).catch(() => {
            throw new SubmissionError({
                _error: 'Username or password is invalid'
            })
        });
    }
};

export const userSetId = (userId) => {
    return {
        type: USER_SET_ID,
        userId
    }
};

export const userProfileRequest = () => {
    return {
        type: USER_PROFILE_REQUEST
    }
};

export const userProfileError = () => {
    return {
        type: USER_PROFILE_ERROR
    }
};

export const userProfileReceived = (userId, userData) => {
    return {
        type: USER_PROFILE_RECEIVED,
        userData,
        userId
    }
};

export const userProfileFetch = (userId) => {
    return (dispatch) => {
        dispatch(userProfileRequest());
        return requests.get(`/users/${userId}`, true).then(
            response => dispatch(userProfileReceived(userId, response))
        ).catch(error => dispatch(userProfileError()))
    }
};

export const workPostAdd = () => ({
    type: WORK_POST_ADD,
    data: {
        id: Math.floor(Math.random() * 100 +3),
        title: 'A new post added!'
    }
});