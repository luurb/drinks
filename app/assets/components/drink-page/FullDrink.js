import React, { useEffect, useState } from 'react';
import { useLocation } from 'react-router-dom';
import axios from 'axios';
import Review from './Review';

const FullDrink = ({ drink }) => {
   const [reviews, setReviews] = useState([]);
   const location = useLocation();
   const { products } = location.state || {};
   const categoriesColors = [
      {
         name: 'słodki',
         color: 'yellow',
      },
      {
         name: 'kwaśny',
         color: 'green',
      },
      {
         name: 'orzeźwiający',
         color: 'blue',
      },
      {
         name: 'lekki',
         color: 'turquoise',
      },
      {
         name: 'mocny',
         color: 'red',
      },
   ];

   useEffect(() => {
      const fetchReview = async (iri) => {
         try {
            const response = await axios.get(iri);
            fetchedReviews.push(response.data);
            // setReviews([...reviews, response.data])
         } catch (error) {
            fetchedReviews.push({ error: 'Something went wrong' });
         }
      };

      let fetchedReviews = [];
      let fetchedPromises = drink.reviews.map((iri) => fetchReview(iri));

      Promise.all(fetchedPromises).then(() => setReviews(fetchedReviews));
   }, []);

   const getCategoryColor = (categoryToFind) =>
      categoriesColors.find((category) => category.name == categoryToFind.name)
         .color;

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
   };

   const returnRatingIcons = () => {
      let icons = [];
      for (let i = 0; i < Math.round(drink.avgRating); i++) {
         icons.push(<i className="fa-solid fa-whiskey-glass" key={i}></i>);
      }

      return icons;
   };

   const returnSpecificRating = () => {
      let ratings = [];
      for (let i = 5; i > 0; i--) {
         ratings.push(
            <div className="full-drink__rating" key={i}>
               <i className="fa-solid fa-whiskey-glass"></i>
               <div className="full-drink__rating-numbers-box">
                  <span className="full-drink__rating-number">{i}</span>
                  <span className="full-drink__rating-counter">
                     ({drink.ratingsStats[i]})
                  </span>
               </div>
            </div>
         );
      }

      return ratings;
   };

   const returnRatingBars = () => {
      let bars = [];
      for (let i = 5; i > 0; i--) {
         let width =
            drink.ratingsNumber == 0
               ? 0
               : drink.ratingsStats[i] / drink.ratingsNumber;
         width *= 100;
         bars.push(
            <div
               className="full-drink__rating-bar"
               style={{ width: `${width}%` }}
               key={i}
            ></div>
         );
      }

      return bars;
   };

   return (
      <div className="full-drink">
         {console.log(drink)}
         <div className="full-drink__wrapper">
            <img
               className="full-drink__img"
               src={drink.image}
               alt="Zdjęcie drinka"
               loading="lazy"
            />
            <div className="full-drink__text-box">
               <div className="full-drink__name">
                  {drink.categories.map((category, index) => (
                     <div
                        key={index}
                        className="full-drink__disc"
                        style={{
                           background: `var(--${getCategoryColor(category)})`,
                        }}
                     ></div>
                  ))}
                  {drink.name}
               </div>
               <div className="full-drink__social-wrapper">
                  <span className="full-drink__author">
                     @{drink.author.username}
                  </span>
                  <div className="drink__social-box">
                     <i className="fa-solid fa-whiskey-glass"></i>
                     <span>{drink.ratingsNumber}</span>
                     <span className="drink__rate-text d-block">
                        {getRatingText(drink.ratingsNumber)}
                     </span>
                  </div>
                  <div className="drink__social-box">
                     <i className="fa-solid fa-comment"></i>
                     <span>{drink.reviewsNumber}</span>
                     <span className="drink__comment-text d-block">
                        {getReviewText(drink.reviewsNumber)}
                     </span>
                  </div>
               </div>
               <div className="full-drink__products">
                  <span>Składniki:</span>
                  <div className="drink__products-box">
                     {products
                        ? products.map((product) => (
                             <div
                                key={product.id}
                                className={
                                   product.active
                                      ? 'drink__active-product full-drink__product'
                                      : 'drink__product full-drink__product'
                                }
                             >
                                {product.name}
                             </div>
                          ))
                        : drink.products.map((product) => (
                             <div
                                key={product.id}
                                className="drink__product full-drink__product"
                             >
                                {product.name}
                             </div>
                          ))}
                  </div>
               </div>
               <p className="full-drink__desc">{drink.description}</p>
               <div className="full-drink__header">Przygotowanie</div>
               <ul className="full-drink__list full-drink__desc">
                  {drink.preparation
                     .split('***')
                     .slice(1)
                     .map((paragraph, index) => (
                        <li key={index}>{paragraph}</li>
                     ))}
               </ul>
               <div className="full-drink__header full-drink__rating-header">
                  Oceny od barmanów
               </div>
               <div className="full-drink__rating-box">
                  <div className="full-drink__rating">
                     {returnRatingIcons()}
                     <span>3 na 5 ocen</span>
                  </div>
                  <div className="full-drink__rating-wrapper">
                     <div className="full-drink__specific-rating">
                        {returnSpecificRating()}
                     </div>
                     <div className="full-drink__specific-rating full-drink__bars-box">
                        {returnRatingBars()}
                     </div>
                  </div>
               </div>
               <div className="full-drink__header full-drink__comments-header">
                  Opinie
               </div>
               <div className="full-drink__comments-wrapper">
                  {reviews &&
                     reviews.map((review) => (
                        <Review review={review} key={review.id} />
                     ))}
               </div>
            </div>
         </div>
      </div>
   );
};

export default FullDrink;
