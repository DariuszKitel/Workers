import React from "react";
import classNames from "classnames";
export class Paginator extends React.Component {
    constructor(props) {
        super(props);
        const {pageCount} = this.props;
        this.range = [];

        for (let i = 1; i <= pageCount; i++) {
            this.range.push(i);
        }
    }

    render() {
        const {currentPage, setPage} = this.props;
        return(
            <nav>
                <ul className="pagination">
                    <li className="page-item">
                        <button className="page-link">
                            Poprzednia
                        </button>
                    </li>

                    {
                        this.range.map(page => {
                            const onClick = () => {
                                setPage(page);
                            };
                            return (
                                <li key={page} className={classNames('page-item', {active: currentPage === page})}>
                                    <button className="page-link" onClick={onClick}>
                                        {page}
                                    </button>
                                </li>
                            );
                        })
                    }

                    <li className="page-item">
                        <button className="page-link">
                            Następna
                        </button>
                    </li>
                </ul>
            </nav>
        )
    }
}