import React from 'react';
import {questionListFetch, questionListUnload} from "../actions/actions";
import {connect} from "react-redux";
import {Spinner} from "./Spinner";
import {QuestionList} from "./QuestionList";
import QuestionForm from "./QuestionForm";

const mapeStateToProps = state => ({
    ...state.questionList,
    isAuthenticated: state.auth.isAuthenticated
});

const mapDispatchToProps = {
    questionListFetch,
    questionListUnload
};
class QuestionListContainer extends React.Component {
    componentDidMount() {
        this.props.questionListFetch(this.props.workPostId);
    }

    componentWillUnmount() {
        this.props.questionListUnload();
    }

    render() {
        const {isFetching, questionList, isAuthenticated, workPostId} = this.props;
        if (isFetching) {
            return (<Spinner/>);
        }
        return (
            <div>
                <QuestionList questionList={questionList}/>
                {isAuthenticated && <QuestionForm workPostId={workPostId}/>}
            </div>
            )
    }
}

export default connect(mapeStateToProps, mapDispatchToProps)(QuestionListContainer);