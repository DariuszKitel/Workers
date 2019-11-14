import React from 'react';
import {postFetch, postUnload} from "../actions/actions";
import {connect} from "react-redux";
import {Post} from "./Post";

const mapeStateToProps = state => ({
    ...state.post
});

const mapDispatchToProps = {
    postFetch,
    postUnload
};
class PostContainer extends React.Component {
    componentDidMount() {
        this.props.postFetch(this.props.match.params.id);
    }

    componentWillUnmount() {
        this.props.postUnload();
    }

    render() {
        const {isFetching, post} = this.props;
        return (<Post isFetching={isFetching} post={post} />);
    }
}

export default connect(mapeStateToProps, mapDispatchToProps)(PostContainer);