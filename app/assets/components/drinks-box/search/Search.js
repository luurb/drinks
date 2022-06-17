import React from 'react';
import Product from './Product';

const Search = () => {
  return (
    <div className="search">
      <form>
        <label htmlFor="search" className="search__header">
          Wybierz produkty
        </label>
        <div className="search__box">
          <input
            id="search"
            className="search__input"
            placeholder="Wpisz produkt"
          />
        </div>
      </form>
      <div className="search__products-box">
        <Product name="whiskey" />
        <Product name="sok z cytryny" />
        <Product name="sok z cytryny" />
        <Product name="sok z cytryny" />
        <Product name="sok z cytryny" />
        <Product name="sok z cytryny" />
        <Product name="sok z cytryny" />
      </div>
    </div>
  );
};

export default Search;
