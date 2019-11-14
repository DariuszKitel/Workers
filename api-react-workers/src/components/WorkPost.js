import React from 'react';

class WorkPost extends React.Component{
    constructor(props) {
        super(props);
        console.log(props);
    }
    render() {

        const {posts} = this.props;

        return (<div>
            <ul>
                {posts && posts.map(post => (<li key={post.id}>{post.title}</li>))}
            </ul>
        </div>)
    }
}

export default WorkPost;