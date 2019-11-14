import React from 'react';
import {Link} from "react-router-dom";

export default class Header extends React.Component {
    render() {
        return (
            <nav className="navbar navbar-dark bg-dark">
                <Link to="/" className="navbar-brand">
                    Workers
                </Link>

                <span className="navbar-text">
                    <Link to="/login">Log in</Link>
                </span>
            </nav>
        )
    }
}