import React from 'react';
import WorkPost from "./WorkPost";
import {workPostFetch} from "../actions/actions";
import {connect} from "react-redux";
import {Spinner} from "./Spinner";

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

        if (isFetching) {
            return (<Spinner/>);
        }

        return (<WorkPost posts={posts} />)
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(WorkPostContainer);