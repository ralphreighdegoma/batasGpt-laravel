const axios = require('axios');
const cheerio = require('cheerio');
const fs = require('fs');
const mysql = require('mysql2');

// Base URL
const BASE_URL = 'https://lawphil.net/judjuris/judjuris.html';

// Array to store all jurisprudence links
let jurisprudence_links = [];

// Array to store final case data
let cases = [];

// Function to fetch and parse HTML
async function fetchHTML(url) {
  try {
    console.log(`Fetching URL: ${url}`);
    const { data } = await axios.get(url);
    return cheerio.load(data);
  } catch (error) {
    console.error(`Error fetching ${url}:`, error.message);
    return null;
  }
}

// Step 1: Visit the main page and get all jurisprudence links
async function getJurisprudenceLinks() {
  console.log('Starting to fetch jurisprudence links...');
  const $ = await fetchHTML(BASE_URL);
  

  if (!$) return;

  // Step 2: Find all links with class "off_n1" inside a table
  $('table a.off_n1').each((index, element) => {
   
    if (index !== 1) return;

  
    const link = $(element).attr('href');
    
    console.log(`https://lawphil.net/judjuris/${link}`);
    jurisprudence_links.push(`https://lawphil.net/judjuris/${link}`);
  });

  console.log(`Found ${jurisprudence_links.length} jurisprudence links.`);

  // Step 4: Process each jurisprudence link
  for (const link of jurisprudence_links) {
    console.log(`Processing jurisprudence link: ${link}`);
    await processJurisprudencePage(link);
  }

  // Step 8: Write the final data to a JSON file
  console.log('Saving data to database...');
  fs.writeFileSync('cases.json', JSON.stringify(cases, null, 2));
  saveToMySQL(cases);
  console.log('Data saved to cases.json');



}

// Step 5: Process each jurisprudence page
async function processJurisprudencePage(url) {
  console.log(`Processing jurisprudence page: ${url}`);
  const $ = await fetchHTML(url);
  if (!$) return;

  // Step 6: Find the link with class "off" under a table
  const casePageLink = $('table a.off').attr('href');
  //get webpage title because its states "Year 2021 Philippine Jurisprudence" so second word is the year
  

  const casePageLinkUrl =  url.substring(0, url.lastIndexOf('/'))+"/"+casePageLink;
 
  console.log("HERRRREEEEEEEEE")
  console.log(`casePageLinkUrl: ${casePageLinkUrl}`);

  if (!casePageLink) return;

  const fullCasePageLink = casePageLinkUrl;
  console.log(`Found case page link: ${fullCasePageLink}`);
  await processCasePage(fullCasePageLink);
}

// Step 6: Process the case page
async function processCasePage(url) {
  console.log(`Processing case page: ${url}`);
  const newCases = [];
  const $ = await fetchHTML(url);
  if (!$) return;

  // Step 7: Find all <tr> with class "xy"
  $('tr.xy').each((index, element) => {
    const case_number = $(element).find('td').eq(0).text().trim();
    const caseLink = $(element).find('a').attr('href');
    let title = $(element).find('td').eq(1).text().trim();
    title = title.replace(/\s*vs\.\s*/, ' vs ');
    const date = $(element).find('td').eq(0).contents().last().text().trim();
    
    if (caseLink) {
      const fullCaseLink =  url.substring(0, url.lastIndexOf('/'))+"/"+caseLink;

      newCases.push({
        case_number,
        title,
        date,
        link: fullCaseLink,
        content: null, // Will be populated in the next step
      });
      console.log(`Added case: ${case_number} with link: ${fullCaseLink}`);
    }
  });

  cases.push(...newCases);

  // Step 8: Visit each case link and extract the blockquote content
  for (const caseData of newCases) {
    console.log(`Processing case content for: ${caseData.case_number}`);
    await processCaseContent(caseData);
  }
}

// Step 8: Extract blockquote content from the case page
async function processCaseContent(caseData) {
  console.log(`Fetching content for case: ${caseData.case_number}`);

  const $ = await fetchHTML(caseData.link);
  if (!$) return;

  // Capture text inside blockquote, fallback to other selectors if needed
  let blockquoteContent = $('blockquote').first().html();

  if (!blockquoteContent) {
    // Fallback option if blockquote is empty
    blockquoteContent = $('div.content').text().trim() || 'No content found.';
  }

  caseData.content = blockquoteContent;
  console.log(`Processed case: ${caseData.case_number}`);
}


//write a function to save it on mysql
async function saveToMySQL(cases) {


  const db = mysql.createConnection({
    host: process.env.DB_HOST || '127.0.0.1', // Use 'mysql' as the host if using Laravel Sail
    port: process.env.DB_PORT || 3306,
    user: process.env.DB_USERNAME || 'sail',
    password: process.env.DB_PASSWORD || 'password',
    database: process.env.DB_DATABASE || 'city-ai',
  });
  
  // Connect to MySQL
  db.connect((err) => {
    if (err) {
      console.error('Error connecting to MySQL:', err.stack);
      return;
    }
    console.log('Connected to MySQL database');
  });

  const query = `
    INSERT INTO cases (case_number, title, date, link, content)
    VALUES (?, ?, ?, ?, ?)
  `;


  //foreach cases
  cases.forEach((caseData) => {
    const caseValues = [
      caseData.case_number,
      caseData.title,
      caseData.date,
      caseData.link,
      caseData.content
    ];
    db.query(query, caseValues, (err, results) => {
      if (err) {
        console.error('Error saving case to MySQL:', err);
      } else {
        console.log(`Case saved to MySQL with ID: ${results.insertId}`);
      }
    });
  });

  
}

// Start the scraping process
//console.log('Starting scraping process...');
//get cases from cases.json
//const cases2 = require('./cases.json');
//saveToMySQL(cases2);
getJurisprudenceLinks();
