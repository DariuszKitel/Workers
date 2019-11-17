import React from 'react';
import {questionListFetch, questionListUnload} from "../actions/actions";
import {connect} from "react-redux";
import {Spinner} from "./Spinner";
import {QuestionList} from "./QuestionList";

const mapeStateToProps = state => ({
    ...state.questionList
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
        const {isFetching, questionList} = this.props;
        if (isFetching) {
            return (<Spinner/>);
        }
        return (<QuestionList questionList={questionList} />);
    }
}

export default connect(mapeStateToProps, mapDispatchToProps)(QuestionListContainer);