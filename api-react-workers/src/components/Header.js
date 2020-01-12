import React from 'react';
import {Link} from "react-router-dom";
import {Spinner} from "./Spinner";

export default class Header extends React.Component {
    renderUser() {
        const {userData} = this.props;

        if (null === userData) {
            return (<Spinner />)
        }
        return (
            <span>
                Hello {userData.name}, &nbsp;
            </span>
        );
    }

    render() {
        const {isAuthenticated, logout} = this.props;
        return (
            <nav className="navbar navbar-dark bg-dark">
                <Link to="/" className="navbar-brand">
                    Workers
                </Link>

                <span className="navbar-text">
                    {isAuthenticated ? <span>Witaj użytkowniku! <button className="btn btn-link btn-sm" href="#" onClick={logout}>Logout</button></span>
                        : <Link to="/login">Log in</Link>}
                </span>
            </nav>
        );
    }
}