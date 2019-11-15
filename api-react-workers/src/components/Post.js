import React from 'react';
import timeago from 'timeago.js';
import {Message} from "./Message";

export class Post extends React.Component {
    render() {
        const {post} = this.props;

        if (null === post) {
            return (<Message message="Work post doesn't exist..."/>);
        }

        return (
            <div className="card mb-3 mt-3 shadow-sm">
                <div className="card-body">
                    <h2>{post.title}</h2>
                    <p className="card-text">{post.content}</p>
                    <p className="card-text border-top">
                        <small className="text-muted">
                            {timeago().format(post.published)}
                        </small>
                    </p>
                        <p className="card-text text-right">
                        <small className="text-muted">
                            {post.author.name}
                        </small>
                        </p>
                </div>
            </div>
        )
    }
}