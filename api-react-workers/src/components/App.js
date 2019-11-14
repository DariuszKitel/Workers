import React from 'react';
import {Route, Switch} from "react-router";
import Login from "./Login";
import WorkPostContainer from "./WorkPostContainer";
import Header from "./Header";
import PostContainer from "./PostContainer";

class App extends React.Component{
    render() {
        return (
          <div>
            <Header/>
            <Switch>
                <Route path="/login" component={Login}/>
                <Route path="/work-post/:id" component={PostContainer}/>
                <Route path="/" component={WorkPostContainer}/>
            </Switch>
          </div>
        )
    }
}

export default App;