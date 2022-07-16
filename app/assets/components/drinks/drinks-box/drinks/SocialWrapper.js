import React from 'react';

const SocialWrapper = (props) => {
    return (
        <div className="drink__social-wrapper">
            <span className="drink__rate">{props.avgRating}</span>
            <div className="drink__social-box">
                <i className="fa-solid fa-whiskey-glass"></i>
                <span>{props.ratingsNumber}</span>
                <span className="drink__rate-text">ocen</span>
            </div>
            <div className="drink__social-box">
                <i className="fa-solid fa-comment"></i>
                <span>{props.reviewsNumber}</span>
                <span className="drink__comment-text">komentarze</span>
            </div>
        </div>
    );
};

export default SocialWrapper;
