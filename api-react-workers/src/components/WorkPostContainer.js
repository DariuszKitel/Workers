import React from 'react';
import WorkPost from "./WorkPost";
import {workPostAdd, workPostFetch} from "../actions/actions";
import {connect} from "react-redux";

const mapStateToProps = state => ({
    ...state.workPost
});

const mapDispatchToProps = {
    workPostAdd,
    workPostFetch
}

class WorkPostContainer extends React.Component{
    componentDidMount() {
        setTimeout(this.props.workPostAdd, 10000);
        this.props.workPostFetch();
    }

    render() {
        const {posts, isFetching} = this.props;

        return (<WorkPost posts={posts} isFetching={isFetching} />)
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(WorkPostContainer);