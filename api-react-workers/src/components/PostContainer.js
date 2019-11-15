import React from 'react';
import {postFetch, postUnload} from "../actions/actions";
import {connect} from "react-redux";
import {Post} from "./Post";
import {Spinner} from "./Spinner";

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
        if (isFetching) {
            return (<Spinner/>);
        }
        return (<Post post={post} />);
    }
}

export default connect(mapeStateToProps, mapDispatchToProps)(PostContainer);