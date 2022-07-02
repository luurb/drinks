import React from 'react';

const SocialWrapper = () => {
    return (
        <div className="drink__social-wrapper">
            <span className="drink__rate">4.24</span>
            <div className="drink__social-box">
                <i className="fa-solid fa-martini-glass"></i>
                <span>14</span>
                <span className="drink__rate-text">ocen</span>
            </div>
            <div className="drink__social-box">
                <i className="fa-solid fa-comment"></i>
                <span>4</span>
                <span className="drink__comment-text">komentarze</span>
            </div>
        </div>
    );
};

export default SocialWrapper;
