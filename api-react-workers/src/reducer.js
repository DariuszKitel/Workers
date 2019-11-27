import {combineReducers} from "redux";
import workPost from "./reducers/workPost";
import post from "./reducers/post";
import questionList from "./reducers/questionList";
import {reducer as formReducer} from 'redux-form';
import auth from "./reducers/auth";
import {routerReducer} from "react-router-redux";

export default combineReducers({
    workPost,
    post,
    questionList,
    auth,
    router: routerReducer,
    form: formReducer
});