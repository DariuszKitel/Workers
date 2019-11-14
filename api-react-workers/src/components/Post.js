import React from 'react';

export class Post extends React.Component {
    render() {
        const {post, isFetching} = this.props;
        
        if (isFetching) {
            return (<div><i className="fas fa-spinner fa-spin"/></div>)
        }
        
        if (null === post) {
            return (<div>Work post doesn't exist</div>);
        }

        return (
            <div>
                {post.title}
            </div>
        )
    }
}