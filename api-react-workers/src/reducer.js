import {combineReducers} from "redux";
import workPost from "./reducers/workPost";
import post from "./reducers/post";

export default combineReducers({
    workPost,
    post
});