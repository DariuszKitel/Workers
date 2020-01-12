import React from "react";
import {Field, reduxForm} from "redux-form";
import {connect} from "react-redux";
import {renderField} from "../form";
import {questionAdd} from "../actions/actions";

const mapDispatchToProps = {
    questionAdd
};

class QuestionForm extends React.Component {
    onSubmit(values) {
        const {questionAdd, workPostId, reset} = this.props;
        return questionAdd(values.content, workPostId).then(() => reset());
    }
    render() {
        const {handleSubmit, submitting} = this.props;

        return (<div className="card mb-3 mt-3 shadow-sm">
            <div className="card-body">
                <form onSubmit={handleSubmit(this.onSubmit.bind(this))}>
                    <Field name="content" label="Type your question:" type="textarea" component={renderField}/>
                    <button type="submit" className="btn btn-primary btn-big btn-block"
                    disabled={submitting}>
                        Dodaj zapytanie
                    </button>
                </form>
            </div>
        </div>
        );
    }
}

export default reduxForm({
    form: "QuestionForm"
})(connect(null, mapDispatchToProps)(QuestionForm))