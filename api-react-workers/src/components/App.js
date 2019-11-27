import React from 'react';
import {Route, Switch} from "react-router";
import Login from "./Login";
import WorkPostContainer from "./WorkPostContainer";
import Header from "./Header";
import PostContainer from "./PostContainer";
import {requests} from "../agent";
import {connect} from "react-redux";
import {userProfileFetch} from "../actions/actions";

const mapStateToProps = state => ({
    ...state.auth
});

const mapDispatchToProps = {
  userProfileFetch
};

class App extends React.Component{
    constructor(props) {
        super(props);
        const token = window.localStorage.getItem('jwtToken');

        if (token) {
            requests.setToken(token);
        }
    }

    componentDidUpdate(prevProps) {
        const {userId, userProfileFetch} = this.props;

        if (prevProps.userId !== userId && userId !== null) {
            console.log(`Old userId ${prevProps.userId}`);
            console.log(`New userId ${userId}`);
            userProfileFetch(userId);
        }
    }

    render() {
        const {isAuthenticated} = this.props;
        return (
          <div>
            <Header isAuthenticated={isAuthenticated}/>
            <Switch>
                <Route path="/login" component={Login}/>
                <Route path="/work-post/:id" component={PostContainer}/>
                <Route path="/" component={WorkPostContainer}/>
            </Switch>
          </div>
        )
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(App);