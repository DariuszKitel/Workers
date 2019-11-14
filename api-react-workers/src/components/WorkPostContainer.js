import React from 'react';
import WorkPost from "./WorkPost";
import {workPostFetch} from "../actions/actions";
import {connect} from "react-redux";

const mapStateToProps = state => ({
    ...state.workPost
});

const mapDispatchToProps = {
    workPostFetch
};

class WorkPostContainer extends React.Component{
    componentDidMount() {
        this.props.workPostFetch();
    }

    render() {
        const {posts, isFetching} = this.props;

        return (<WorkPost posts={posts} isFetching={isFetching} />)
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(WorkPostContainer);