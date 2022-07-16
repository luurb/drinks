import React from 'react';

const Review = ({ review }) => {
   const returnRatingInReview = () => {
      let ratings = [];
      const drinkRating = review.drinkRating;
      for (let i = 0; i < drinkRating; i++) {
         ratings.push(
            <i className="fa-solid fa-whiskey-glass" key={i}></i>
         )
      }
      for (let i = 0; i < 5 - drinkRating; i++) {
         ratings.push(
            <i className="fa-solid fa-whiskey-glass transparent" key={i+10}></i>
         )
      }

      return ratings;
   };

   const getReviewCreationDate = () => {
      let date = new Date(Date.parse(review.createdAt)).toLocaleString('pl-PL');

      return date.slice(0, -3);
   }

   return (
      <div className="full-drink__comments-box">
         <div className="full-drink__comment-header">
            <div className="full-drink__comment-rating-box">
               {returnRatingInReview()}
            </div>
            <span>{review.author.username}</span>
         </div>
         <div className="full-drink__comment-title-box">
            <span>{review.title}</span>
            <span className="full-drink__comment-date">{getReviewCreationDate()}</span>
         </div>
         <div className="full-drink__comment">
            {review.review}
         </div>
      </div>
   );
};

export default Review;
