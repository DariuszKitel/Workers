import React from 'react';
import {Link} from "react-router-dom";

export default class Header extends React.Component {
    render() {
        const {isAuthenticated} = this.props;
        return (
            <nav className="navbar navbar-dark bg-dark">
                <Link to="/" className="navbar-brand">
                    Workers
                </Link>

                <span className="navbar-text">
                    {isAuthenticated ? <span>Hello User!</span> : <Link to="/login">Log in</Link>}
                </span>
            </nav>
        );
    }
}