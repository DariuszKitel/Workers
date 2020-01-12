import React from 'react';
import {Link} from "react-router-dom";
import {Spinner} from "./Spinner";

export default class Header extends React.Component {
    renderUser() {
        const {userData} = this.props;

        if (null === userData) {
            return (<Spinner />)
        }
        return (<span>Hello {userData.name}</span>)

    }
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