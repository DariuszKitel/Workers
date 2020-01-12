import {USER_LOGIN_SUCCESS, USER_LOGOUT, USER_PROFILE_ERROR} from "./actions/constants";
import {requests} from "./agent";

export const tokenMiddleware = store => next => action => {
    switch (action.type) {
        case USER_LOGIN_SUCCESS:
            window.localStorage.setItem('jwtToken', action.token);
            window.localStorage.setItem('userId', action.userId);
            requests.setToken(action.token);
            console.log('From middleware: ' + action.token);
            break;
        case USER_LOGOUT:
            window.localStorage.removeItem('jwtToken');
            window.localStorage.removeItem('userId');
            requests.setToken(null);
            break;
        case USER_PROFILE_ERROR:
            console.log(store.getState().auth.userId === action.userId);
            break;
        default:
    }

    next(action);
};