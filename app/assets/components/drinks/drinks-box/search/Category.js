import React from 'react';

const Category = ({ category, setCategories }) => {
   return (
      <div
         className="search__category"
         onClick={() => setCategories(category.id)}
      >
         <span
            className="search__category-disc"
            style={{
               border: `2px solid var(--${category.color})`,
               background: category.active && `var(--${category.color})`,
            }}
         ></span>
         <span className="search__category-text">{category.name}</span>
      </div>
   );
};

export default Category;
