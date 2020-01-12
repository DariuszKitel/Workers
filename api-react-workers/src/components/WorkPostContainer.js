import React from 'react';
import WorkPost from "./WorkPost";
import {workPostFetch, workPostSetPage} from "../actions/actions";
import {connect} from "react-redux";
import {Spinner} from "./Spinner";
import {Paginator} from "./Paginator";

const mapStateToProps = state => ({
    ...state.workPost
});

const mapDispatchToProps = {
    workPostFetch, workPostSetPage
};

class WorkPostContainer extends React.Component{
    componentDidMount() {
        this.props.workPostFetch();
    }

    componentDidUpdate(prevProps) {
        const {currentPage, workPostFetch} = this.props;

        if (prevProps.currentPage !== currentPage) {
            workPostFetch(currentPage);
        }
    }

    render() {
        const {posts, isFetching, workPostSetPage, currentPage} = this.props;

        if (isFetching) {
            return (<Spinner/>);
        }

        return (
            <div>
                <WorkPost posts={posts}/>
                <Paginator currentPage={currentPage} pageCount={10} setPage={workPostSetPage}/>
            </div>
            )
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(WorkPostContainer);