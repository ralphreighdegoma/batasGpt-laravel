const axios = require('axios');
const cheerio = require('cheerio');
const fs = require('fs');

// Base URL
const BASE_URL = 'https://lawphil.net/judjuris/judjuris.html';

// Arrays to store links and case data
let jurisprudence_links = [];
let cases = [];

// Fetch HTML with immediate logging
async function fetchHTML(url) {
  try {
    console.log(`Fetching URL: ${url}`);
    const { data } = await axios.get(url, { timeout: 10000 });
    return cheerio.load(data);
  } catch (error) {
    console.error(`Error fetching ${url}:`, error.message);
    return null;
  }
}

// Main function
async function getJurisprudenceLinks() {
  console.log('Starting to fetch jurisprudence links...');
  const $ = await fetchHTML(BASE_URL);
  if (!$) return;

  $('table a.off_n1').each((index, element) => {
    const link = $(element).attr('href');
    if (link) jurisprudence_links.push(`https://lawphil.net/judjuris/${link}`);
  });

  console.log(`Found ${jurisprudence_links.length} jurisprudence links.`);

  for (const link of jurisprudence_links) {
    console.log(`Processing jurisprudence link: ${link}`);
    await processJurisprudencePage(link);
  }

  // Save the results
  console.log('Saving data to cases.json...');
  fs.writeFileSync('cases.json', JSON.stringify(cases, null, 2));
  console.log('Data saved to cases.json');
}

async function processJurisprudencePage(url) {
  console.log(`Processing jurisprudence page: ${url}`);
  const $ = await fetchHTML(url);
  if (!$) return;

  const casePageLink = $('table a.off').attr('href');
  if (!casePageLink) return;

  const fullCasePageLink = `https://lawphil.net/judjuris/${casePageLink}`;
  console.log(`Found case page link: ${fullCasePageLink}`);
  await processCasePage(fullCasePageLink);
}

async function processCasePage(url) {
  console.log(`Processing case page: ${url}`);
  const $ = await fetchHTML(url);
  if (!$) return;

  $('tr.xy').each((index, element) => {
    const caseNumber = $(element).find('a').text().trim();
    const caseLink = $(element).find('a').attr('href');
    const date = $(element).find('td').eq(1).text().trim();

    if (caseLink) {
      const fullCaseLink = `https://lawphil.net/judjuris/${caseLink}`;
      cases.push({
        caseNumber,
        date,
        link: fullCaseLink,
        content: null,
      });
      process.stdout.write(`\nAdded case: ${caseNumber} with link: ${fullCaseLink}`);
    }
  });

  for (const caseData of cases) {
    await processCaseContent(caseData);
  }
}

async function processCaseContent(caseData) {
  console.log(`Fetching content for case: ${caseData.caseNumber}`);
  const $ = await fetchHTML(caseData.link);
  if (!$) return;

  const blockquoteContent = $('blockquote').html();
  caseData.content = blockquoteContent;
  console.log(`Processed case: ${caseData.caseNumber}`);
}

// Start
getJurisprudenceLinks();
