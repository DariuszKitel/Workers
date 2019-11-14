import React from 'react';
import {Route, Switch} from "react-router";
import Login from "./Login";
import WorkPostContainer from "./WorkPostContainer";

class App extends React.Component{
    render() {
        return (
          <div>
              Hello!
            <Switch>
                <Route path="/login" component={Login}/>
                <Route path="/" component={WorkPostContainer}/>
            </Switch>
          </div>
        )
    }
}

export default App;