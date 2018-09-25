'use strict';

// Typeahead Demo
// =============================================================

var typeaheadDemo = {
  init: function init() {

    this.bindUIActions();
  },
  bindUIActions: function bindUIActions() {

    // event handlers
    this.basic();
    this.bloodhound();
    this.prefetch();
    this.remote();
    this.suggestions();
    this.customTemplates();
    this.multipleDatasets();
    this.scrollable();
  },
  basic: function basic() {
    var substringMatcher = function substringMatcher(strs) {
      return function findMatches(q, cb) {
        // an array that will be populated with substring matches
        var matches = [];
        // regex used to determine if a string contains the substring `q`
        var substrRegex = new RegExp(q, 'i');
        // iterate through the pool of strings and for any string that
        // contains the substring `q`, add it to the `matches` array
        $.each(strs, function (i, str) {
          if (substrRegex.test(str)) {
            matches.push(str);
          }
        });
        cb(matches);
      };
    };
    var states = this.getStates();

    $('#the-basics').typeahead({
      hint: true,
      highlight: true,
      minLength: 1
    }, {
      name: 'states',
      source: substringMatcher(states)
    });
  },
  bloodhound: function bloodhound() {
    // constructs the suggestion engine
    var states = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.whitespace,
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      // `states` is an array of state names defined in "The Basics"
      local: this.getStates()
    });

    $('#bloodhound').typeahead({
      hint: true,
      highlight: true,
      minLength: 1
    }, {
      name: 'states',
      source: states
    });
  },
  prefetch: function prefetch() {
    var countries = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.whitespace,
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      // url points to a json file that contains an array of country names, see
      // https://github.com/twitter/typeahead.js/blob/gh-pages/data/countries.json
      prefetch: 'assets/data/countries.json'
    });

    // passing in `null` for the `options` arguments will result in the default
    // options being used
    $('#prefetch').typeahead(null, {
      name: 'countries',
      source: countries
    });
  },
  remote: function remote() {
    var bestPictures = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      prefetch: 'assets/data/films/post_1960.json',
      remote: {
        url: 'assets/data/films/queries/%QUERY.json',
        wildcard: '%QUERY'
      }
    });

    $('#remote').typeahead(null, {
      name: 'best-pictures',
      display: 'value',
      source: bestPictures
    });
  },
  suggestions: function suggestions() {
    var nflTeams = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('team'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      identify: function identify(obj) {
        return obj.team;
      },
      prefetch: 'assets/data/nfl.json'
    });

    var nflTeamsWithDefaults = function nflTeamsWithDefaults(q, sync) {
      if (q === '') {
        sync(nflTeams.get('Detroit Lions', 'Green Bay Packers', 'Chicago Bears'));
      } else {
        nflTeams.search(q, sync);
      }
    };

    $('#default-suggestions').typeahead({
      minLength: 0,
      highlight: true
    }, {
      name: 'nfl-teams',
      display: 'team',
      source: nflTeamsWithDefaults
    });
  },
  customTemplates: function customTemplates() {
    var bestPictures = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      prefetch: 'assets/data/films/post_1960.json',
      remote: {
        url: 'assets/data/films/queries/%QUERY.json',
        wildcard: '%QUERY'
      }
    });

    $('#custom-templates').typeahead(null, {
      name: 'best-pictures',
      display: 'value',
      source: bestPictures,
      templates: {
        empty: ['<div class="empty-message">', 'unable to find any Best Picture winners that match the current query', '</div>'].join('\n'),
        suggestion: Handlebars.compile('<div><strong>{{value}}</strong> – {{year}}</div>')
      }
    });
  },
  multipleDatasets: function multipleDatasets() {
    var nbaTeams = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('team'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      prefetch: 'assets/data/nba.json'
    });

    var nhlTeams = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('team'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      prefetch: 'assets/data/nhl.json'
    });

    $('#multiple-datasets').typeahead({
      highlight: true
    }, {
      name: 'nba-teams',
      display: 'team',
      source: nbaTeams,
      templates: {
        header: '<h3 class="tt-menu-header">NBA Teams</h3>'
      }
    }, {
      name: 'nhl-teams',
      display: 'team',
      source: nhlTeams,
      templates: {
        header: '<h3 class="tt-menu-header">NHL Teams</h3>'
      }
    });
  },
  scrollable: function scrollable() {
    var countries = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.whitespace,
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      // url points to a json file that contains an array of country names, see
      // https://github.com/twitter/typeahead.js/blob/gh-pages/data/countries.json
      prefetch: 'assets/data/countries.json'
    });

    $('#scrollable-dropdown-menu').typeahead(null, {
      name: 'countries',
      limit: 10,
      source: countries
    });
  },
  getStates: function getStates() {
    return ['Alabamaxxx', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'];
  }
};

typeaheadDemo.init();