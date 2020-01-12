import React from 'react';
import {Message} from "./Message";
import timeago from 'timeago.js';

export class QuestionList extends React.Component {
    render() {
        const {questionList} = this.props;

        if (null === questionList || 0 === questionList.length) {
            return (<Message message="No question for post"/>);
        }

        return (
            <div className="card mb-3 mt-3 shadow-sm">
                {questionList.map(question => {
                    return (
                      <div className="card-body border-bottom" key={question.id}>
                          <p className="card-text mb-0">
                              {question.content}
                          </p>
                          <p className="card-text">
                              <small className="text-muted">
                                  {timeago().format(question.published)} by&nbsp;
                                  {question.author.name}
                              </small>
                          </p>
                      </div>
                    );
                })}
            </div>
        )
    }
}