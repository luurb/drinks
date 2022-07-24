import React, { useCallback } from 'react';
import SocialWrapper from './SocialWrapper';
import { Link } from 'react-router-dom';

const Drink = React.forwardRef((props, ref) => {
   const getLink = () => `/drinki/${props.drink.id}/${props.drink.name}`;

   return (
      <Link
         className="drink"
         to={getLink()}
         state={{ products: props.drink.products }}
         ref={ref}
      >
         <img
            className="drink__img"
            src={props.drink.image}
            alt="Zdjęcie drinka"
            loading="lazy"
         />
         <div className="drink__name-box">
            <div className="drink__categories-box">
               {props.drink.categories.map((category, index) => (
                  <div
                     key={index}
                     className="drink__category-disc"
                     style={{ background: `var(--${category.color})` }}
                  ></div>
               ))}
            </div>
            <span className="drink__name">{props.drink.name}</span>
         </div>
         <SocialWrapper
            avgRating={props.drink.avgRating}
            ratingsNumber={props.drink.ratingsNumber}
            reviewsNumber={props.drink.reviewsNumber}
         />
         <div className="drink__desc">
            <div className="drink__desc-text">
               {props.drink.shortDescription}
            </div>
            <div className="drink__products-wrapper">
               <span className="drink__products-header">Składniki:</span>
               <div className="drink__products-box">
                  {props.drink.products.map((product) => (
                     <span
                        key={product.id}
                        className={
                           product.active
                              ? 'drink__active-product'
                              : 'drink__product'
                        }
                     >
                        {product.name}
                     </span>
                  ))}
               </div>
            </div>
         </div>
      </Link>
   );
});

export default Drink;
