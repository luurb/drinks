import React from 'react';

const SocialWrapper = (props) => {
   const getReviewText = (reviewsNumber) => {
      switch (reviewsNumber) {
         case 1:
            return 'opinia';
         case 2:
         case 3:
         case 4:
            return 'opinie';
         default:
            return 'opinii';
      }
   };

   const getRatingText = (ratingNumber) => {
      switch (ratingNumber) {
         case 1:
            return 'ocena';
         case 2:
         case 3:
         case 4:
            return 'oceny';
         default:
            return 'ocen';
      }
   }

    return (
        <div className="drink__social-wrapper">
            <span className="drink__rate">{props.avgRating}</span>
            <div className="drink__social-box">
                <i className="fa-solid fa-whiskey-glass"></i>
                <span>{props.ratingsNumber}</span>
                <span className="drink__rate-text">
                    {getRatingText(props.ratingNumber)}
                </span>
            </div>
            <div className="drink__social-box">
                <i className="fa-solid fa-comment"></i>
                <span>{props.reviewsNumber}</span>
                <span className="drink__comment-text">
                    {getReviewText(props.reviewsNumber)}
                </span>
            </div>
        </div>
    );
};

export default SocialWrapper;
