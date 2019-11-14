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
        console.log(this.props);
        return (<WorkPost posts={this.props.posts} />)
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(WorkPostContainer);