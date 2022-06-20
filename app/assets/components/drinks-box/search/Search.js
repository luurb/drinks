import React from 'react';
import Product from './Product';
import Category from './Category';
import SearchBox from './SearchBox';

const Search = () => {
   const categories = [
      {
         id: 1,
         name: 'słodki',
         color: 'yellow',
      },
      {
         id: 2,
         name: 'kwaśny',
         color: 'green',
      },
      {
         id: 3,
         name: 'orzeźwiający',
         color: 'blue',
      },
      {
         id: 4,
         name: 'lekki',
         color: 'turquoise',
      },
      {
         id: 5,
         name: 'mocny',
         color: 'red',
      },
   ];

   return (
      <div className="search">
         <SearchBox />
         <div className="search__products-box">
            <Product name="whiskey" />
            <Product name="sok z cytryny" />
            <Product name="sok z cytryny" />
            <Product name="sok z cytryny" />
            <Product name="sok z cytryny" />
         </div>
         <div className="search__categories-box">
            {categories.map((category) => (
               <Category
                  key={category.id}
                  name={category.name}
                  color={category.color}
               />
            ))}
         </div>
      </div>
   );
};

export default Search;
